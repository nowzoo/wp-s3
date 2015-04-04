<?php
namespace NowZoo\WPS3;

class Plugin{
    const SITE_OPTION_AWS= 'nowzoo-aws-s3';



    private static $instance = null;

    public static function inst(){
        if (is_null(self::$instance)){
            self::$instance = new Plugin;
        }
        return self::$instance;
    }

    private function __construct(){
       AdminSettingsForm::inst();
    }

    public static function lib_path($p = false){
        require dirname(dirname(dirname(__DIR__))) . '/';
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
        $option = get_site_option(self::SITE_OPTION_AWS);
        if (! is_array($option)){
            $option = array();
        }
        return array_merge($defaults, $option);
    }

    public static function set_aws_option($option){
        $defaults = array(
            'key' => '',
            'secret' => '',
            'bucket' => ''
        );
        if (! is_array($option)){
            $option = array();
        }
        update_site_option(self::SITE_OPTION_AWS, array_merge($defaults, $option));
    }
}