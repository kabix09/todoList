<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'index.php';

$name = $_GET['name'] ?? "";

header('Content-Type: application/json');
echo json_encode($session[$name]??"");
?>
