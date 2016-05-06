<?php

error_reporting(E_ALL);

ini_set('date.timezone', "UTC");

require_once "vendor/autoload.php";
require_once 'read-config.php';

function get_keys() {
    $public_filename = __DIR__."/keys/pub.pem";
    $private_filename = __DIR__."/keys/oauth2.key";
    if(!file_exists($public_filename)) {
        echo "public key not found in $public_filename";
        die;
    }

    if(!file_exists($private_filename)) {
        echo "private key not found in $private_filename";
        die;
    }

    return array(
        'public_key'  => file_get_contents($public_filename),
        'private_key' => file_get_contents($private_filename));
}

OAuth2\Autoloader::register();

$config = get_config();

$server_config = array(
    'use_openid_connect' => true,
    'issuer' => $config['openid']['issuer']
);

$storage = new OAuth2\Storage\Pdo($config['db']);
$server = new OAuth2\Server($storage,$server_config);

$keyStorage = new OAuth2\Storage\Memory(array('keys' => get_keys()));

$server->addStorage($keyStorage, 'public_key');

//$server->addGrantType(new OAuth2\OpenID\GrantType\AuthorizationCode($storage)); // or any grant type you like!
//$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage)); // or any grant type you like!


