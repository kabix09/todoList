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

try {
    if($id === NULL || $owner === NULL)
        throw new RuntimeException("script error - missing elements");

    if($owner != $_SESSION['user']->getNick())
        throw new RuntimeException("script error - incorrect user");

    if(!in_array($id, array_map(function($element){
                                    return $element->getId();
                                }, $_SESSION['tasks'])
    ))
        throw new RuntimeException("script error - incorrect task");

    $taskRepository = new TaskRepository(new Connection(include DB_CONFIG));
    if($taskRepository->remove(
        [
            "WHERE" =>NULL,
            "AND" => ["id = '{$id}'", "owner = '{$owner}'"]
        ]))
    {
            // no need to remove from session because
            // index.php automatically refresh task list
        header("Location: ../index.php");
    }else{
        throw new RuntimeException("system error - couldn't remove task {$id}");
    }

}catch (\Exception $e){
    var_dump($e->getMessage());
    die();
}



