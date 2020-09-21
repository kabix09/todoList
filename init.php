<?php
require_once __DIR__ . "./vendor/autoload.php";

session_start();

define("ROOT_PATH", str_replace("\\", "/", dirname(__FILE__)) . '/');

// replace paths with 4_SERVER['SERVER_NAME'];
define("DB_CONFIG", ROOT_PATH . './config/db.config.php');

require_once ROOT_PATH . './config/messages.config.php';