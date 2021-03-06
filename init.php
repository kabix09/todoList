<?php
require_once __DIR__ . "./vendor/autoload.php";

ini_set('session.auto_start', 0);
//ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cookie_domain', "todolist.localhost:8000");
ini_set('session.cookie_lifetime', ini_get('session.gc_maxlifetime'));
ini_set('session.cookie_httponly', 1);
ini_set('session.entropy_length', 32);
ini_set('session.entropy_file', "/dev/urandom");
ini_set('session.hash_function', "sha256");
ini_set('session.hash_bits_per_character', 5);

//session_start();

define("SITE_ROOT", str_replace("\\", DIRECTORY_SEPARATOR, __DIR__) . DIRECTORY_SEPARATOR);

// replace paths with $_SERVER['SERVER_NAME'];
require_once SITE_ROOT . './config/messages.config.php';