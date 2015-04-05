<?php
namespace NowZoo\WPS3;

use NowZoo\WPUtils\WPUtils;

class AdminSettingsPanel {


    private $message = '';
    private $error = false;

    private static $instance = null;



    public static function inst(){
        if (is_null(self::$instance)){
            self::$instance = new AdminSettingsPanel;
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
        add_action('init', array($this, 'action_init'));
        if (is_multisite()){
            add_action('network_admin_menu', array($this, 'action_admin_menu'));
        } else {
            add_action('admin_menu', array($this, 'action_admin_menu'));
        }
    }
    public function action_admin_menu(){
        if (is_multisite()){
            $cap = 'manage_network';
        } else {
            $cap = 'administrator';
        }
        add_submenu_page('settings.php', 'AWS S3', 'AWS S3', $cap, Plugin::SITE_OPTION_AWS, array($this, 'panel'));
    }
    public function panel(){
        $option = Plugin::get_aws_option();
        $error = $this->error;
        $message = $this->message;
        Plugin::require_lib_path('includes/admin_panel_settings.php', compact('option', 'error', 'message'));
    }


    public function action_init(){
        if (! is_admin()) return;
        if (! isset($_GET['page']) || Plugin::SITE_OPTION_AWS !== $_GET['page']) return;
        if (! WPUtils::is_submitting()) return;
        if (is_multisite()){
            $cap = 'manage_network';
        } else {
            $cap = 'administrator';
        }
        if (! current_user_can($cap)) return;

        if (! wp_verify_nonce($_POST[Plugin::SITE_OPTION_AWS . '_nonce'], Plugin::SITE_OPTION_AWS) ){
            return;
        }
        $option = WPUtils::trim_stripslashes_deep($_POST[Plugin::SITE_OPTION_AWS]);
        $validated = Plugin::validate_aws_option($error, $option);
        Plugin::set_aws_option($option, $validated);
        $this->message = $validated ? 'AWS S3 options updated!' : $error;
        $this->error = ! $validated;
    }


}