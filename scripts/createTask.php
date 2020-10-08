<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Manager\UserManager;
use App\Module\Form\Task\Create;
use App\Module\SessionObserver;
use App\Module\ErrorObserver;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Token\Token;

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
    $createTask = new Create($formData, new Connection(include DB_CONFIG), $session['user']);

        // create usefully observers
    new ErrorObserver($createTask);
    new SessionObserver($createTask);

        // execute create task logic
    if($createTask->handler($session['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS))
    {
        unset($session['token']);


            // 2 - refresh task list

            // if exists remove old tasks
        if(isset($session["tasks"]))
            unset($session["tasks"]);

            // download all tasks - v 2.0
        $connection = new Connection(include DB_CONFIG);

        $userManager = new UserManager($session['user'],
                                        new UserRepository($connection));
        $userManager->getUserTasks(new TaskRepository($connection));

        foreach ($session['user']->getTaskCollection() as $task)
            $session["tasks"] = array_merge($session["tasks"] ?? array(), [$task]);

            // 3 - set header
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