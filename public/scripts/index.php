<?php
require_once $_SERVER['DOCUMENT_ROOT'] . './init.php';

use ConnectionFactory\Connection;
use App\Service\Session\Session;

define('RECAPTCHA', ROOT_PATH . './config/reCaptcha.config.php');

$session = new Session();
$connection = new Connection(include DB_CONFIG);