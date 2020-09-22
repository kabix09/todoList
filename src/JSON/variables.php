<?php
require_once '../../vendor/autoload.php';
use App\Session\Session;

$name = $_GET['name'] ?? "";
$session = new Session();

header('Content-Type: application/json');

echo json_encode($session[$name]??"");
?>
