<?php
define("DB_CONFIG", __DIR__ . '/../../config/db.config.php');
require_once '../../vendor/autoload.php';
use App\Service\Session\Session;

$name = $_GET['name'] ?? "";
$session = new Session();

header('Content-Type: application/json');

echo json_encode($session[$name]??"");
?>
