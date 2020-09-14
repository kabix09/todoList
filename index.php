<?php

use App\Connection\Connection;
use App\Repository\TaskRepository;

require_once './init.php';

if(isset($_SESSION['user']) && $_SESSION['user']->getStatus() !== "active"){
    header("Location: ./templates/accountStatus.php");
    exit();
}

if(isset($_SESSION['user']))   //  && !isset($_SESSION['tasks'])
{
        // if exists remove old tasks
    if(isset($_SESSION['tasks']))
        unset($_SESSION['tasks']);

        // download all tasks
    $taskRepo = new TaskRepository(new Connection(include DB_CONFIG));
    $tasks = $taskRepo->find(array(), [
        "WHERE" => ["owner", "= '{$_SESSION['user']->getNick()}'"]
    ]);

    foreach ($tasks as $task)
        $_SESSION['tasks'][] = $task;
}

include ROOT_PATH . './templates/index.php';