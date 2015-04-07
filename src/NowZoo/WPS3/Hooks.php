<?php
namespace NowZoo\WPS3;
class Hooks {
    private static $instance = null;

    public static function inst(){
        if (is_null(self::$instance)){
            self::$instance = new Hooks;
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'plugins_loaded', array($this, 'action_plugins_loaded') );
    }

    public function action_plugins_loaded(){
        //bail if we the options aren't valid or we haven't validated the options...
        if (! Plugin::get_aws_option_valid()) return;

        add_filter('wp_update_attachment_metadata', array($this, 'filter_wp_update_attachment_metadata'), 10, 2);
        add_filter('wp_get_attachment_url', array( $this, 'filter_wp_get_attachment_url' ), 9, 2);
        add_action('delete_attachment', array( $this, 'action_delete_attachment' ), 20);
    }

    /**
     * @param string $path
     * @return bool
     * @throws \Exception
     */
    private function sync_dir($path = ''){
        $option = Plugin::get_aws_option();
        $client = Plugin::get_client($option);
        $key_prefix = null;
        $upload_paths = wp_upload_dir();
        $local_dir = $upload_paths['basedir'];
        if (! empty($path)){
            $path = trim($path, '/');
            $local_dir .= '/' . $path;
            $key_prefix = $path;
        }
        throw new \Exception('local dir:' . $local_dir);
        try{
            $client->uploadDirectory($local_dir, $option['bucket'], $key_prefix, array(
                'params'      => array('ACL' => 'public-read'),
                'concurrency' => 20
            ));
            return true;
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function filter_wp_update_attachment_metadata($data, $post_id){
        $attached = get_post_meta($post_id, '_wp_attached_file', true);
        if (! $attached) return $data;
        $path = dirname($attached);
        try{
            $this->sync_dir($path);
            return $data;
        } catch(\Exception $e){
            return $data;
        }
    }

    /**
     * @param $url
     * @param $post_id
     * @return string
     */
    public function filter_wp_get_attachment_url($url, $post_id){
        $option = Plugin::get_aws_option();
        $attached = get_post_meta($post_id, '_wp_attached_file', true);
        if (! $attached) return $url;
        if (is_multisite()){
            if (BLOG_ID_CURRENT_SITE != get_current_blog_id()){
                $attached = 'sites/' . get_current_blog_id() . '/' . $attached;
            }
        }
        if ($option['cloudfront_enabled'] && ! empty($option['cloudfront_domain'])){
            $base = $option['cloudfront_domain'];
        } else {
            $base = 's3.amazonaws.com/' . $option['bucket'];
        }
        return 'https://' . $base .  '/' . $attached;
    }

    /**
     * @param \Aws\S3\S3Client $client
     * @param $bucket
     * @param $dir
     * @param $file
     */
    private function delete_one($client, $bucket, $dir, $file){
        try{
            $client->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => implode('/', array($dir, $file))
            ));
        } catch (\Exception $e){
            //fail silently?
        }
    }

    /**
     * @param $post_id
     */
    public function action_delete_attachment($post_id){
        $option = Plugin::get_aws_option();
        $client = Plugin::get_client($option);
        $attachment_meta = wp_get_attachment_metadata($post_id);
        $backup_sizes = get_post_meta($post_id, '_wp_attachment_backup_sizes', true);
        if ($attachment_meta){
            $dir = explode('/', $attachment_meta['file']);
            $file = array_pop($dir);
            $dir = implode('/', $dir);
            $this->delete_one($client, $option['bucket'], $dir, $file);
            if (isset($attachment_meta['sizes']) && is_array($attachment_meta['sizes'])){
                foreach($attachment_meta['sizes'] as $size){
                    $this->delete_one($client, $option['bucket'], $dir, $size['file']);
                }
            }
            if ($backup_sizes && is_array($backup_sizes)){
                foreach($backup_sizes as $size){
                    $this->delete_one($client, $option['bucket'], $dir, $size['file']);
                }
            }
        }
    }
}