<?php
define('DB_CONFIG_FILE', __DIR__ . "/config/db.config.php");

require_once __DIR__ . "/vendor/autoload.php";

use App\Connection\ {Connection, Finder};

$conn = new Connection(include DB_CONFIG_FILE);
$conn->connect();

$stmt = $conn->getConnection()->prepare(
    Finder::select("user")::getSQL()
);
$stmt->execute();

echo "<pre>";
    var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";