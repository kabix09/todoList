<?php
require_once  '../../init.php';

use App\Connection\Connection;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;

$session = new Session();
$tasksArray = [];
if(isset($session['user']) && isset($session['tasks']))
{
    // download all tasks - v 2.0
    $connection = new Connection(include DB_CONFIG);

    if(!empty($session['user']->getTaskCollection()))
    {
        $userManager = new UserManager($session['user'],
                                        new UserRepository($connection));
        $userManager->getUserTasks(new TaskRepository($connection));
    }

    foreach ($session['user']->getTaskCollection() as $task)
    {
        $tasksArray[] = $task;
    }

}

header('Content-Type: application/json');
echo json_encode($tasksArray);
