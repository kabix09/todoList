<?php
require_once '../init.php';

use App\Module\SessionObserver;
use App\Module\Task\Create;
use App\Module\ErrorObserver;
use App\Session\Session;
use App\Token\Token;
use App\Entity\User;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

$session = new Session();

if (!isset($_POST['hidden']))
    $session['token'] = (new Token())->generate()->binToHex()->getToken();

if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET') {
    include ROOT_PATH . './templates/createTask.php';
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../templates/errors/404.php");
} else {
        // 0 - remove old errors
    if (isset($session['createErrors']))
        unset($session['createErrors']);

    unset($_POST['submit']);

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create login logic instance
    $createTask = new Create($formData, include DB_CONFIG, $session['user']);

        // create usefully observers
    new ErrorObserver($createTask);
    new SessionObserver($createTask);

        // execute create task logic
    if($createTask->taskHandler($session['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS,
        $session['user']->getNick()))
    {
        unset($session['token']);

        $session["tasks"] = array_merge($session["tasks"] ?? array(), [$createTask->getTask()]);

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