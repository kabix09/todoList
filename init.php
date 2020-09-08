<?php
require_once __DIR__ . "./vendor/autoload.php";

session_start();

define("ROOT_PATH", str_replace("\\", "/", dirname(__FILE__)) . '/');

$_SESSION['ROOT_PATH'] = ROOT_PATH;

require_once $_SESSION['ROOT_PATH'] . './config/messages.config.php';