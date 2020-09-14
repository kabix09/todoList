<?php
require_once '../init.php';

use App\Manager\TaskManager;
use App\Token\Token;
use App\Filter\Filter;
use App\Connection\Connection;
use App\Repository\TaskRepository;

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
    if (isset($_SESSION['taskForm']))
        unset($_SESSION['taskForm']);

    unset($_POST['submit']);

        // 1 - check hidden token
    if (!isset($_SESSION['token']))
        exit("token doesn't exists on server side ://");

    if (sodium_compare(
            (new Token($_SESSION['token']))->hash()->getToken(),
            (new Token($_POST['hidden']))->decode()->getToken()
        ) !== 0
    ) throw new RuntimeException('detected cross-site attack on login form');

    unset($_SESSION['token']);
    unset($_POST['hidden']);

    // remove POST data and operate on local variable
    $formData = array();

    foreach ($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

    //-------------------------------------------------------------------------------------

        // 2 - filter data & 3 - valid data
    $filter = new Filter(
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS);
    $filter->process($formData);

    foreach ($filter->getMessages() as $key => $value)
    {
        $_SESSION['taskForm'][$key] = $value;
    }

    //  if there is any error message, redirect to login form
    if(isset($_SESSION['taskForm'])) {
        header("Location: ./createTask.php");
        exit();
    }

    $taskRepo = new TaskRepository(new Connection(include DB_CONFIG));
    $task = $taskRepo->fetchByTitle($formData['title']);

    if ($task)
    {
        $_SESSION['taskForm']['title'] = ['this task already exists'];
        header("Location: ./createTask.php");
        exit();
    }

    $taskManager = new TaskManager($formData,
                        new TaskRepository(
                            new Connection(include DB_CONFIG)));


    //$taskManager->setCreateDate();
    $taskManager->setAuthor($_SESSION['user']->getNick(), TRUE);
    $taskManager->setStatus();

    $newTask = $taskManager->return();

    if($taskRepo->insert($newTask))
    {
        $tasks = $taskRepo->find(array(), [
                               "WHERE" => ["owner", "= '{$_SESSION['user']->getNick()}'"]
                            ]);

        foreach ($tasks as $task)
            $_SESSION['tasks'][] = $task;

        header("Location: ../index.php");
    }else{
        throw new RuntimeException("system error - couldn't create new task :/");
    }

}