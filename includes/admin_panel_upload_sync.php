<?php
use NowZoo\WPS3\Plugin;
use NowZoo\WPS3\AdminUploadsSyncPanel;
/**
 * @var array $option
 * @var bool $error
 * @var string $message
 */
?>



<div class="wrap">
    <h2>Sync Uploads with Amazon S3 Bucket</h2>

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
        wp_nonce_field(AdminUploadsSyncPanel::PAGE, AdminUploadsSyncPanel::PAGE . '_nonce');
        ?>
        <h3>Synchronize WordPress to S3</h3>
        <input type="hidden" name="subaction" value="sync_to_s3">
        <p>
            Make sure all the attachments
            currently in your site's uploads
            directory also exist in your S3 bucket.
        </p>
        <?php
        submit_button('Sync Now');
        ?>
    </form>
</div>


