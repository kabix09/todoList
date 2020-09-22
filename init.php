<?php
require_once __DIR__ . "./vendor/autoload.php";

ini_set('session.auto_start', 0);
//ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cookie_domain', "todolist.localhost:8000");
ini_set('session.cookie_httponly', 1);
ini_set('session.entropy_length', 32);
ini_set('session.entropy_file', "/dev/urandom");
ini_set('session.hash_function', "sha256");
ini_set('session.hash_bits_per_character', 5);

//session_start();

define("ROOT_PATH", str_replace("\\", "/", dirname(__FILE__)) . '/');

// replace paths with 4_SERVER['SERVER_NAME'];
define("DB_CONFIG", ROOT_PATH . './config/db.config.php');

require_once ROOT_PATH . './config/messages.config.php';