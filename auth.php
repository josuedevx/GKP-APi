<?php
require_once __DIR__ . '/vendor/autoload.php';

$oAuth2Credential = (new \Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder())->fromFile()->build();
$googleAdsClient = (new \Google\Ads\GoogleAds\Lib\V16\GoogleAdsClientBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->build();

echo "Autenticación con Google Ads exitosa ✅";
?>