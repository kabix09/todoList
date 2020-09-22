<?php
require_once '../vendor/autoload.php';
use App\Session\Session;

$session = new Session();
if(isset($session['user'])) {
    $session->destroy();
}

header("Location: ../index.php");