<?php

require '../server.php';

$request = OAuth2\Request::createFromGlobals();

$server->handleTokenRequest($request)->send();
