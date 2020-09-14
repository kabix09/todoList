<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Repository\TaskRepository;

if(!isset($_SESSION['user']) && !isset($_SESSION['tasks']))
{
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? NULL;
$owner = $_GET['owner'] ?? NULL;

if($id === NULL || $owner === NULL)
    throw new RuntimeException("script error - unknown element");

$taskRepository = new TaskRepository(new Connection(include DB_CONFIG));
if($taskRepository->remove(
    [
        "WHERE" =>NULL,
        "AND" => ["id = '{$id}'", "owner = '{$owner}'"]
    ]))
{
    $i=0;
    foreach ($_SESSION['tasks'] as $task)
    {
        if($task->getId() == $id)
            break;
        $i++;
    }
    unset($_SESSION['tasks'][$i]);

    header("Location: ../index.php");
}else{
    throw new RuntimeException("system error - couldn't remove task {$id}");
}



