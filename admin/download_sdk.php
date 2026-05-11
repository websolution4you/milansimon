<?php
$url = 'https://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.phar';
$dest = __DIR__ . '/aws.phar';

$ch = curl_init($url);
$fp = fopen($dest, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);
curl_close($ch);
fclose($fp);
echo "AWS SDK bolo uspesne stiahnute do $dest";
