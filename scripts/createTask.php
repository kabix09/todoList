<?php
require_once '../init.php';

use App\Module\Task\Create;
use App\Module\ErrorObserver;
use App\Token\Token;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

if (!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET') {
    include ROOT_PATH . './templates/createTask.php';
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../templates/errors/404.php");
} else {
        // 0 - remove old errors
    if (isset($_SESSION['createErrors']))
        unset($_SESSION['createErrors']);

    unset($_POST['submit']);

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create login logic instance
    $createTask = new Create($formData, include DB_CONFIG);

        // create usefully observers
    new ErrorObserver($createTask);

        // execute create task logic
    if($createTask->taskHandler($_SESSION['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS,
        $_SESSION['user']->getNick()))
    {
        unset($_SESSION['token']);

        $_SESSION['tasks'][] = $createTask->getTask();

            // 2 - set header
        header("Location: ../index.php");
    }else
        header("Location: ./createTask.php");

        /*
         * $tasks = $taskRepo->find(array(), [
                               "WHERE" => ["owner", "= '{$_SESSION['user']->getNick()}'"]
                            ]);
            foreach ($tasks as $task)
                $_SESSION['tasks'][] = $task;
        */
}