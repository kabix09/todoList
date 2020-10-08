<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Logger\Logger;
use App\Logger\MessageSheme;
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
        "className" => __CLASS__,
        "functionName" => __FUNCTION__
    ]);

    die("The user requesting access to the session could not be verified");
}

try{
    $new = new UserManager($session['user'], new UserRepository(new Connection(include DB_CONFIG)));

    if($new->activateTheAccount())
    {
            // log event
        $config = new MessageSheme($session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
        $logger->info("Successfully activated account", [$config]);
            // redirect page
        header("Location: ../index.php");
    }
    else
        throw new RuntimeException("The account with id: {$session['user']->getId()} couldn't be activated");
}catch (\Exception $e)
{
    $config = new MessageSheme($session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
    $logger->warning($e->getMessage(), [$config]);
}


