<?php
require_once '../../vendor/autoload.php';

use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Entity\Mapper\TaskMapper;
use App\Service\Manager\TaskManager;
use App\Service\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\Session\Session;

$session = new Session();
$tasksArray = [];
if(isset($session['user']))
{
    // download all tasks - v 2.0
    $connection = new Connection(Config::init()::module(Constants::DATABASE)::get());

    // no matter if the list is empty, data must be fetched every time the script is called
    $userManager = new UserManager($session['user'],
                                    new UserRepository($connection));

    $taskRepository = new TaskRepository($connection);

    $userManager->getUserTasks($taskRepository);


    foreach ($session['user']->getTaskCollection() as $key => $object){
            // 1 check time & fix status
        $taskManager = new TaskManager($object, $taskRepository);

        $status = $object->getStatus();

        if($status === 'planned' && !empty($object->getStartDate()))
        {
            $startTime = new DateTime($object->getStartDate());
            $currentTime = new DateTime();

            if($currentTime->getTimestamp() - $startTime->getTimestamp() >= 0)
            {
                $taskManager->changeStatus('started');
                $taskManager->update();
            }
        }else if($status === 'started' && !empty($object->getTargetEndDate())){
            $endDate = new DateTime($object->getTargetEndDate());
            $currentTime = new DateTime();

            if($currentTime->getTimestamp() - $endDate->getTimestamp() >= 0)
            {
                $taskManager->changeStatus('finished');
                $taskManager->update();
            }
        }

        // 1 map object
        $tasksArray[$key] = TaskMapper::entityToArray($object);
    }
}

header('Content-Type: application/json');
echo json_encode($tasksArray);
