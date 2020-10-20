<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

use App\Connection\Connection;
use App\Session\Session;

$session = new Session();
$connection = new Connection(include DB_CONFIG);