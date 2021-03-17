<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'index.php';

use App\Entity\Task;
use App\Entity\Mapper\TaskMapper;
use App\Service\EntityManager\ { Task\TaskManager, Task\Builder\TaskBuilder};
use App\Service\EntityManager\ { User\UserManager, User\Builder\UserBuilder};
use App\Repository\{TaskRepository, UserRepository};

$tasksArray = [];

if(isset($session['user']))
{
    // download all tasks - v 2.0

    // no matter if the list is empty, data must be fetched every time the script is called
    $userManager = new UserManager(
        new UserBuilder($session['user']),
        new UserRepository($connection),
        new TaskRepository($connection)
    );

    $userManager->loadUserTasks($session['user']->getNick());

    $session['user'] = $userManager->return();      // update user instance handled in session

    $taskRepository = new TaskRepository($connection);

    foreach ($session['user']->getTaskCollection() as $key => $task){
            // 1 check time & fix status

        $taskManager = new TaskManager(
            new TaskBuilder($task),
            $taskRepository
        );

        $status = $task->getStatus();

        if($status === 'planned' && !empty($task->getStartDate()))
        {
            $startTime = new DateTime($task->getStartDate());
            $currentTime = new DateTime();

            if($currentTime->getTimestamp() - $startTime->getTimestamp() >= 0)
            {
                $taskManager->prepareInstance(
                    [
                        Task::MAPPING['status'] => "started"
                    ]
                );
                $taskManager->update();
            }
        }else if($status === 'started' && !empty($task->getTargetEndDate())){
            $endDate = new DateTime($task->getTargetEndDate());
            $currentTime = new DateTime();

            if($currentTime->getTimestamp() - $endDate->getTimestamp() >= 0)
            {
                $taskManager->prepareInstance(
                    [
                        Task::MAPPING['status'] => "finished"
                    ]
                );
                $taskManager->update();
            }
        }

        // map object
        $tasksArray[$key] = TaskMapper::convertEntityToArray($task);
    }
}

header('Content-Type: application/json');
echo json_encode($tasksArray);
