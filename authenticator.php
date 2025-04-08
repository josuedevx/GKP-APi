<?php
require 'vendor/autoload.php';

use Google\Auth\OAuth2;

$clientId = '';
$clientSecret = '';

// url de autenticación
$oauth2 = new OAuth2([
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'authorizationUri' => 'https://accounts.google.com/o/oauth2/auth',
    'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
    'redirectUri' => 'http://localhost',
    'scope' => 'https://www.googleapis.com/auth/adwords',
]);

printf("Abrir esta URL en tu navegador para autorizar la aplicación:\n%s\n", $oauth2->buildFullAuthorizationUri());

echo "Introduce el código de autorización aquí: ";
$authCode = trim(fgets(STDIN));

$oauth2->setCode($authCode);
$authToken = $oauth2->fetchAuthToken();

printf("Tu refresh_token es: %s\n", $authToken['refresh_token']);
