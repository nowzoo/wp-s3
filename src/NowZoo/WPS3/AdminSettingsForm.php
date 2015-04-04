<?php
namespace NowZoo\WPS3;


class AdminSettingsForm {

    private static $instance = null;

    public static function inst(){
        if (is_null(self::$instance)){
            self::$instance = new AdminSettingsForm;
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'wpmu_options', array($this, 'action_wpmu_options') );

    }

    public function action_network_admin_menu(){
        $option = Plugin::get_aws_option();
        Plugin::require_lib_path('includes/admin_settings.php', compact($option));
    }
}