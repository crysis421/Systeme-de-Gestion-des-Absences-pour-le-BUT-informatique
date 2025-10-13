<?php

require '../../mailjet-apiv3-php-no-composer-master/src/Mailjet/autoload.php'; // inclut diverses bibliothèques dont PHPMailer a besoin.

use \Mailjet\Resources;

// getenv will allow us to get the MJ_APIKEY_PUBLIC/PRIVATE variables we created before:

$apikey = getenv('MJ_APIKEY_PUBLIC');
$apisecret = getenv('MJ_APIKEY_PRIVATE');

$mj = new \Mailjet\Client($apikey, $apisecret);

// or, without using environment variables:

$apikey = '2a850caaaa2a49c9073dd6344da067cd';
$apisecret = '6ecb8b7d30709959ad9ee36e4c97dfb2';

$mj = new \Mailjet\Client($apikey, $apisecret);

class EnvoieMail
{


}