<?php
use NowZoo\WPS3\Plugin;
/**
 * @var array $option
 * @var bool $error
 * @var string $message
 */
?>



<div class="wrap">
    <h2>Amazon Web Services S3 Settings</h2>

    <?php
    if (! empty($message)){
        ?>
        <div id="message" class="<?php echo ($error) ? 'error' : 'updated'?>">
            <p><?php echo $message?></p>
        </div>
    <?php
    }
    ?>

    <form method="post">
        <?php
        wp_nonce_field(Plugin::SITE_OPTION_AWS, Plugin::SITE_OPTION_AWS . '_nonce');
        ?>
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
        <?php
        submit_button('Save Settings');
        ?>
    </form>
</div>


