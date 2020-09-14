<?php
require_once '../init.php';
use App\Connection\Connection;
use App\Manager\UserManager;
use App\Repository\UserRepository;

$new = new UserManager(NULL, new UserRepository(new Connection(include DB_CONFIG)));

if($new->activateTheAccount($_SESSION['user']))
    header("Location: ../index.php");
else
    throw new RuntimeException("system error - the account could not be activated");
