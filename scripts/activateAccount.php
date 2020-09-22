<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Session\SessionManager;

$session = new Session();
$sessionManager = new SessionManager($session);

if(!$sessionManager->manage())
{
    // logout and redirect to login page
    die("toDo - user error in remove task script");
}

$new = new UserManager(NULL, new UserRepository(new Connection(include DB_CONFIG)));

if($new->activateTheAccount($session['user']))
    header("Location: ../index.php");
else
    throw new RuntimeException("system error - the account could not be activated");
