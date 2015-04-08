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
                        type="text"
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
                        type="text"
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
                        type="text"
                        >
                </td>
            </tr>


            <tr>
                <th><label for="<?php echo Plugin::SITE_OPTION_AWS?>_cloudfront_enabled">Cloudfront</label> </th>
                <td>
                    <label>
                        <input
                            class="cloudfront_enabled"
                            id="<?php echo Plugin::SITE_OPTION_AWS?>_cloudfront_enabled"
                            name="<?php echo Plugin::SITE_OPTION_AWS?>[cloudfront_enabled]"
                            type="checkbox"
                            <?php if ($option['cloudfront_enabled']) echo ' checked="checked" '?>
                            value="1"
                            >
                        Enable a Cloudfront distribution.
                    </label>

                    <p class="cloudfront_domain_container">
                        <label for="<?php echo Plugin::SITE_OPTION_AWS?>_cloudfront_domain">Cloudfront Domain</label>
                        <input
                            class="cloudfront_domain widefat"
                            id="<?php echo Plugin::SITE_OPTION_AWS?>_cloudfront_domain"
                            name="<?php echo Plugin::SITE_OPTION_AWS?>[cloudfront_domain]"
                            value="<?php echo esc_attr($option['cloudfront_domain'])?>"
                            placeholder="AWS Cloudfront Domain Name"
                            type="text"
                            >
                    </p>


                    <script>
                        jQuery(document).ready(function($){
                            var check = $('.cloudfront_enabled');
                            var inp = $('.cloudfront_domain');
                            var ctr = $('.cloudfront_domain_container');
                            var upd = function(){
                                inp.prop('disabled', ! check.is(':checked'));
                                ctr.toggle(check.is(':checked'));
                            };
                            check.change(upd);
                            upd();
                        });
                    </script>


                </td>
            </tr>

            </tbody>
        </table>
        <?php
        submit_button('Save Settings');
        ?>
    </form>
</div>


