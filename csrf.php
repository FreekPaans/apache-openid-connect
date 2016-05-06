<?php

//double submit cookies (https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)_Prevention_Cheat_Sheet#Double_Submit_Cookies) CSRF protection

define('__CSRF_TOKEN_COOKIE_NAME',"__csrf_token");
define('__CSRF_TOKEN_INPUT_NAME', "__csrf_token");

function generate_csrf_token() {
    //from http://stackoverflow.com/a/31683058/345910
    $token=base64_encode(openssl_random_pseudo_bytes(32));

    return $token;
}

function get_or_create_token() {
    if(isset($_COOKIE[__CSRF_TOKEN_COOKIE_NAME])) {
        return $_COOKIE[__CSRF_TOKEN_COOKIE_NAME];
    }

    $token = generate_csrf_token();
    setcookie(__CSRF_TOKEN_COOKIE_NAME, $token);

    return $token;
}

function csrf_input_field() {
    return sprintf('<input name="%s" value="%s" type="hidden" />', __CSRF_TOKEN_INPUT_NAME, get_or_create_token());
}

function csrf_bad_request($msg) {
    http_response_code(400);
    echo $msg;
    die;
}

function verify_csrf_token() {
    if(!isset($_COOKIE[__CSRF_TOKEN_COOKIE_NAME])) {
        csrf_bad_request("csrf cookie not present");
    }
    if(!isset($_POST[__CSRF_TOKEN_INPUT_NAME])) {
        csrf_bad_request("csrf token not present in post");
    }
    if($_COOKIE[__CSRF_TOKEN_COOKIE_NAME]!== $_POST[__CSRF_TOKEN_INPUT_NAME]) {
        csrf_bad_request("invalid csrf token");
    }
}
