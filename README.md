# NowZoo WordPress S3

Stores WordPress uploads in an Amazon S3 bucket. Optionally, enables the use of a Cloudfront distribution.

##Features

- Works on both network snd single WordPress installations. The bucket directly mirrors the `wp-content/uploads` directory.
- Syncs files immediately on upload, edit and delete.
- Can sync all a site's uploads at once -- useful if you are importing a site.

## Installation

````
$ composer require nowzoo/wp-s3
````


Instantiate the admin panels by including this code somewhere (e.g in `wp-content/mu-plugins/index.php`):

````
<?php
NowZoo\WPS3\Plugin::inst();
````

## Setup

- You need an Amazon S3 account.
- Create an S3 bucket for your WordPress installation. Networked installations only need one bucket.
- **Recommended:** Create a dedicated IAM user for the bucket, and attach a policy to the bucket that allows that user to upload and delete objects. [Instructions for bucket policies.](http://docs.aws.amazon.com/AmazonS3/latest/dev/example-bucket-policies.html)
- Enter the AWS user credentials, bucket name and, optionally, a Cloudfront distribution domain...
   - Network installs: /wp-admin/network/settings.php?page=nowzoo-aws-s3
   - Single installs: /wp-admin/settings.php?page=nowzoo-aws-s3   



