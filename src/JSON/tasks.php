<?php
require_once  '../../init.php';

use App\Connection\Connection;
use App\Repository\TaskRepository;
use App\Manager\TaskManager;

$tasksArray = [];
if(isset($_SESSION['user']) && isset($_SESSION['tasks']))
{
    $taskRepo = new TaskRepository(new Connection(include DB_CONFIG));
    $tasksGenerator = $taskRepo->find(array(), [
        "WHERE" => ["owner", "= '{$_SESSION['user']->getNick()}'"]
    ]);

    foreach ($tasksGenerator as $task)
    {
        $tasksArray[] = (new TaskManager($task, $taskRepo))->toArray();
    }
}

header('Content-Type: application/json');
echo json_encode($tasksArray);
