<?php
require_once './init.php';

use App\Session\Session;

$session = new Session();
/*
$sesManager = new SessionManager($session);
if(!$sesManager->manage())
{
    throw new \ErrorException("incompatible browser data");
}
var_dump($session['counter']);
*/

    // chceck account status in purpose to redirect
if(isset($session["user"]) && $session["user"]->getStatus() !== "active"){
    header("Location: ./templates/user/accountStatus.php");
    exit();
}

    // remove form errors handled in session
$sessionKeys = array_keys($session->getSession());
foreach ($sessionKeys as $key)
{
    if(preg_match("/Errors/", $key))
    {
        unset($session[$key]);
    }
}

    // main page
include ROOT_PATH . './templates/index.php';
