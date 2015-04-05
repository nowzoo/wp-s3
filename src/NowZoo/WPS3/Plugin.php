<?php
namespace NowZoo\WPS3;
use Aws\S3\S3Client;

class Plugin{
    const SITE_OPTION_AWS = 'nowzoo-aws-s3';
    const SITE_OPTION_AWS_VALID = 'nowzoo-aws-s3-validated';



    private static $instance = null;

    public static function inst(){
        if (is_null(self::$instance)){
            self::$instance = new Plugin;
        }
        return self::$instance;
    }

    private function __construct(){
       AdminPanel::inst();
    }

    public static function lib_path($p = false){
        $lib_path = dirname(dirname(dirname(__DIR__)));
        if ($p && ! empty($p)){
            $lib_path .= '/' . $p;
        }
        return $lib_path;
    }

    public static function require_lib_path($p = false, $data = array()){

        extract($data);
        require self::lib_path($p);
    }

    public static function get_aws_option(){
        $defaults = array(
            'key' => '',
            'secret' => '',
            'bucket' => ''
        );
        if (is_multisite()){
            $option = get_site_option(self::SITE_OPTION_AWS);
        } else {
            $option = get_option(self::SITE_OPTION_AWS);
        }
        if (! is_array($option)){
            $option = array();
        }
        $option = array_merge($defaults, $option);
        return $option;
    }
    public static function get_aws_option_valid(){
        if (is_multisite()){
            $option = get_site_option(self::SITE_OPTION_AWS_VALID);
        } else {
            $option = get_option(self::SITE_OPTION_AWS_VALID);
        }
        $option = $option ? true : false;
        return $option;
    }

    public static function set_aws_option($option, $validated){
        $defaults = array(
            'key' => '',
            'secret' => '',
            'bucket' => ''
        );
        if (! is_array($option)){
            $option = array();
        }
        $option = array_merge($defaults, $option);
        if (is_multisite()) {
            update_site_option(self::SITE_OPTION_AWS, $option);
            update_site_option(self::SITE_OPTION_AWS_VALID, $validated);
        } else {
            update_option(self::SITE_OPTION_AWS, $option);
            update_option(self::SITE_OPTION_AWS_VALID, $validated);
        }
    }

    public static function validate_aws_option(&$error, $option = null){
        if (! $option){
            $option = self::get_aws_option();
        }

        if (empty($option['key']) || empty($option['secret']) || empty($option['bucket'])){
            $error = 'All AWS S3 fields are required. Enter your access key and secret and the name of the bucket.';
            return false;
        }

        $client = self::get_client($option);
        $test_key = 'test_' . time() . '.txt';

        try{
            $client->putObject(array(
                'Bucket' => $option['bucket'],
                'Key'    => $test_key,
                'Body'   => 'test'
            ));
            $client->deleteObject(array(
                'Bucket' => $option['bucket'],
                'Key' => $test_key
            ));
            return true;
        } catch(\Exception $e){
            $error = 'Something is wrong with the settings you entered. Amazon said: ' . $e->getMessage();
            return false;
        }


    }
    


    public static function get_client($option = null){
        if (! $option){
            $option = self::get_aws_option();
        }
        return S3Client::factory($option);
    }
}