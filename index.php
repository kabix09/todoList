<?php
require_once './init.php';

use App\Connection\Connection;
use App\Repository\TaskRepository;

    // chceck account status in purpose to redirect
if(isset($_SESSION['user']) && $_SESSION['user']->getStatus() !== "active"){
    header("Location: ./templates/accountStatus.php");
    exit();
}

    // remove form errors handled in session
$sessionKeys = array_keys($_SESSION);
foreach ($sessionKeys as $key)
{
    if(preg_match("/Errors/", $key))
    {
        unset($_SESSION[$key]);
    }
}

    // refresh task list if user is logged
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

    // main page
include ROOT_PATH . './templates/index.php';
