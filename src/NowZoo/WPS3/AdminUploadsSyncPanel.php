<?php
namespace NowZoo\WPS3;
use NowZoo\WPUtils\WPUtils;

class AdminUploadsSyncPanel {

    const PAGE = 'nowzoo-aws-s3-sync';

    private $message = '';
    private $error = false;

    private static $instance = null;



    public static function inst(){
        if (is_null(self::$instance)){
            self::$instance = new AdminUploadsSyncPanel;
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'plugins_loaded', array($this, 'action_plugins_loaded') );
    }
    public function action_plugins_loaded(){
        if (! is_admin()){
            return;
        }
        add_action('admin_menu', array($this, 'action_admin_menu'));
        add_action('init', array($this, 'action_init'));
    }
    public function action_admin_menu(){
        $cap = 'administrator';
        add_submenu_page('upload.php', 'AWS S3 Sync', 'AWS S3 Sync', $cap, self::PAGE, array($this, 'panel'));
    }
    public function panel(){
        $option = Plugin::get_aws_option();
        $error = $this->error;
        $message = $this->message;
        Plugin::require_lib_path('includes/admin_panel_upload_sync.php', compact('option', 'error', 'message'));
    }


    public function action_init(){
        if (! is_admin()) return;
        if (! isset($_GET['page']) || self::PAGE !== $_GET['page']) return;
        if (! WPUtils::is_submitting()) return;
        $cap = 'administrator';
        if (! current_user_can($cap)) return;
        if (! wp_verify_nonce($_POST[self::PAGE . '_nonce'], self::PAGE) ){
            return;
        }
        $subaction = trim(stripslashes($_POST['subaction']));
        switch($subaction){
            case 'sync_to_s3':
                try{
                    $this->sync_blog_uploads();
                    $this->message = 'Site uploads synchronized!';
                } catch(\Exception $e){
                    $this->error = true;
                    $this->message = 'Something went wrong. Amazon said: ' . $e->getMessage();
                }
                break;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function sync_blog_uploads(){
        $option = Plugin::get_aws_option();
        $client = Plugin::get_client($option);

        $upload_paths = wp_upload_dir();
        $blog_local_dir = $upload_paths['basedir'];
        if (is_multisite()){
            switch_to_blog(BLOG_ID_CURRENT_SITE);
            $upload_paths = wp_upload_dir();
            $main_local_dir = $upload_paths['basedir'];
            restore_current_blog();
            $key_prefix = trim(str_replace($main_local_dir, '', $blog_local_dir), '/');
            if (empty($key_prefix)) $key_prefix = null;
        } else {
            $key_prefix = null;
        }

        try{
            $client->uploadDirectory($blog_local_dir, $option['bucket'], $key_prefix, array(
                'params'      => array('ACL' => 'public-read'),
                'concurrency' => 20
            ));
            return true;
        } catch(\Exception $e){
            throw $e;
        }
    }

}