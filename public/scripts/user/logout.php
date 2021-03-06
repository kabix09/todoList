<?php
require_once __DIR__ . '/../index.php';

use App\Service\Logger\MessageSheme;
use App\Service\Session\Session;

if(isset($session['user'])) {
    $user = $session['user']->getNick();

    $session->destroy();

    $config = new MessageSheme($user, __CLASS__, __FUNCTION__, TRUE);
    $logger = new \App\Service\Logger\Logger();
    $logger->info("Successfully logout user", [$config]);
}

header("Location: ../../../index.php");