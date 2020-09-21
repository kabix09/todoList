<?php
session_start();
$name = $_GET['name'] ?? "";

header('Content-Type: application/json');

echo json_encode($_SESSION[$name]??"");
?>
