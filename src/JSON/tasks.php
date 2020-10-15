<?php
require_once  '../../init.php';

use App\Connection\Connection;
use App\Entity\Mapper\TaskMapper;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;

$session = new Session();
$tasksArray = [];
if(isset($session['user']))
{
    // download all tasks - v 2.0
    $connection = new Connection(include DB_CONFIG);

    // no matter if the list is empty, data must be fetched every time the script is called
    $userManager = new UserManager($session['user'],
                                    new UserRepository($connection));

    $userManager->getUserTasks(new TaskRepository($connection));

    foreach ($session['user']->getTaskCollection() as $key => $object){
        $tasksArray[$key] = TaskMapper::entityToArray($object);
    }
}

header('Content-Type: application/json');
echo json_encode($tasksArray);
