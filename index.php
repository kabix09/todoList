<?php
define('DB_CONFIG_FILE', __DIR__ . "/config/db.config.php");

require_once __DIR__ . "/vendor/autoload.php";

use App\Connection\ {Connection, QueryBuilder};
use App\Entity\ {User, Base};

$conn = new Connection(include DB_CONFIG_FILE);
$conn->connect();

$stmt = $conn->getConnection()->prepare(
    QueryBuilder::select("user")::getSQL()
);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
    var_dump($result);
echo "</pre>";

$user = new User();
User::arrayToEntity($result, $user);

echo "<pre>";
var_dump($user);
echo "</pre>";

$res = User::entityToArray($user);

echo "<pre>";
var_dump($res);
echo "</pre>";