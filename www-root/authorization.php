<?php

if(!isset($_SERVER['PHP_AUTH_USER'])) {
    echo "No user credentials found, you should configure authentication. See examples/htaccess.";
    die;
}

$user = $_SERVER['PHP_AUTH_USER'];

$current_session_params = session_get_cookie_params();
session_set_cookie_params(
    $current_session_params['lifetime'],
    $current_session_params['path'],
    $current_session_params['domain'],
    $current_session_params['secure'],
    true);


session_start();

require '../server.php';
require '../csrf.php';

$response = new OAuth2\Response();
$request = OAuth2\Request::createFromGlobals();

if(!$server->validateAuthorizeRequest(
        $request,
        $response)) {
        $response->send();
        die;
}

if(empty($_POST)) {
    exit(sprintf('
<form method="post">
Welcome, %s. Do you authorize %s?
%s
<input type="submit" name="authorized" value="yes" />
<input type="submit" name="authorized" value="no" />
</form>', $user, $server->getAuthorizeController()->getClientId(), csrf_input_field()));
}

verify_csrf_token();

$server->handleAuthorizeRequest($request, $response, $_POST['authorized']==='yes',$user);

$response->send();
