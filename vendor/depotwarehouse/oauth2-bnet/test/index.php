<?php

require '../vendor/autoload.php';
require 'config.php';

$provider = new \Depotwarehouse\OAuth2\Client\Provider\BattleNet(
    $config
);

if (isset($_GET['code']) && $_GET['code']) {
    $token = $provider->getAccessToken("authorization_code", [
        'code' => $_GET['code']
    ]);

    $user = $provider->getUserDetails($token);
    var_dump($user);


} else {
    header('Location: ' . $provider->getAuthorizationUrl());
}
