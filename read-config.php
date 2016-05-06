<?php

function get_config() {
    $config = null;
    if(file_exists(__DIR__.'/config.php')) {
        include("config.php");
        return $config;
    }
    if(file_exists(__DIR__.'/config.default.php')) {
        trigger_error("loading config from config.default.php", E_USER_NOTICE);
        include("config.default.php");
        return $config;
    }

    echo "Couldn't find a config file";
    die;
}

