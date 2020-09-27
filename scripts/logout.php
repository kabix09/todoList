<?php
require_once '../init.php';
use App\Session\Session;

$session = new Session();
if(isset($session['user'])) {
    $session->destroy();
}

header("Location: ../index.php");