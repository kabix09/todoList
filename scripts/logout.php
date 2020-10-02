<?php
require_once '../init.php';
use App\Session\Session;

$session = new Session();
if(isset($session['user'])) {
    $user = $session['user']->getNick();

    $session->destroy();

    $logger = new \App\Logger\Logger();
    $logger->info("Successfully logout user", [
        "personalLog" => TRUE,
        "userFingerprint" => $user,
        "fileName" => __FILE__
    ]);
}

header("Location: ../index.php");