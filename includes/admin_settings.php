<?php
use NowZoo\WPS3\Plugin;
/**
 * @var $option
 */
?>

<h3>Amazon S3 Settings</h3>

<table class="form-table">
    <tbody>
    <tr>
        <th><label for="<?php echo Plugin::SITE_OPTION_AWS?>_key">Access Key</label> </th>
        <td>
            <input
                id="<?php echo Plugin::SITE_OPTION_AWS?>_key"
                name="<?php echo Plugin::SITE_OPTION_AWS?>[key]"
                class="widefat"
                value="<?php echo esc_attr($option['key'])?>"
                placeholder="AWS IAM User Access Key"
                >
        </td>
    </tr>
    <tr>
        <th><label for="<?php echo Plugin::SITE_OPTION_AWS?>_secret">Access Secret Key</label> </th>
        <td>
            <input
                id="<?php echo Plugin::SITE_OPTION_AWS?>_secret"
                name="<?php echo Plugin::SITE_OPTION_AWS?>[secret]"
                class="widefat"
                value="<?php echo esc_attr($option['secret'])?>"
                placeholder="AWS IAM User Secret Access Key"
                >
        </td>
    </tr>
    <tr>
        <th><label for="<?php echo Plugin::SITE_OPTION_AWS?>_bucket">S3 Bucket</label> </th>
        <td>
            <input
                id="<?php echo Plugin::SITE_OPTION_AWS?>_bucket"
                name="<?php echo Plugin::SITE_OPTION_AWS?>[bucket]"
                class="widefat"
                value="<?php echo esc_attr($option['bucket'])?>"
                placeholder="AWS S3 Bucket Name"
                >
        </td>
    </tr>
    </tbody>
</table>
