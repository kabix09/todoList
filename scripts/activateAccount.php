<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Logger\Logger;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Session\SessionManager;

$logger = new Logger();
$session = new Session();
$sessionManager = new SessionManager($session);

if(!$sessionManager->manage())
{
    $logger->critical("The user requesting access to the session could not be verified", [
        "userFingerprint" => $_SERVER['REMOTE_ADDR'],
        "fileName" => __FILE__
    ]);

    die("The user requesting access to the session could not be verified");
}

try{
    $new = new UserManager(NULL, new UserRepository(new Connection(include DB_CONFIG)));

    if($new->activateTheAccount($session['user']))
    {
            // log event
        $logger->info("Successfully activated account", [
            "personalLog" => TRUE,
            "userFingerprint" => $session['user']->getNick(),
            "fileName" => __FILE__
        ]);
            // redirect page
        header("Location: ../index.php");
    }
    else
        throw new RuntimeException("The account with id: {$session['user']->getId()} couldn't be activated");
}catch (\Exception $e)
{
    $logger->warning($e->getMessage(), [
        "personalLog" => TRUE,
        "userFingerprint" => $session['user']->getNick(),
        "fileName" => __FILE__
    ]);
}


