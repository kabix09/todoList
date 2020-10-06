<?php
require_once '../init.php';

use App\Logger\MessageSheme;
use App\Session\Session;

$session = new Session();
if(isset($session['user'])) {
    $user = $session['user']->getNick();

    $session->destroy();

    $config = new MessageSheme($user, __CLASS__, __FUNCTION__, TRUE);
    $logger = new \App\Logger\Logger();
    $logger->info("Successfully logout user", [$config]);
}

header("Location: ../index.php");