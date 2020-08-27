<?php
define('DB_CONFIG_FILE', __DIR__ . "/config/db.config.php");

require_once __DIR__ . "/vendor/autoload.php";

use App\Connection\Connection;

$conn = new Connection(include DB_CONFIG_FILE);
$conn->connect();
var_dump($conn->getConnection());