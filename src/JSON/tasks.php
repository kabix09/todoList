<?php
require_once  '../../init.php';

use App\Connection\Connection;
use App\Repository\TaskRepository;
use App\Manager\TaskManager;
use App\Session\Session;

$session = new Session();
$tasksArray = [];
if(isset($session['user']) && isset($session['tasks']))
{
    $taskRepo = new TaskRepository(new Connection(include DB_CONFIG));
    $tasksGenerator = $taskRepo->find(array(), [
        "WHERE" => ["owner", "= '{$session['user']->getNick()}'"]
    ]);

    foreach ($tasksGenerator as $task)
    {
        $tasksArray[] = (new TaskManager($task, $taskRepo))->toArray();
    }
}

header('Content-Type: application/json');
echo json_encode($tasksArray);
