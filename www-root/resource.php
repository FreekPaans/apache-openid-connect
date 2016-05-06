<?php

include '../server.php';

if(!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}

echo 'succes!!11';
