<?php
require_once '../../init.php';

use App\Connection\Connection;
use App\Manager\UserManager;
use App\Module\ErrorObserver;
use App\Module\SessionObserver;
use App\Module\Form\Task\Edit;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Token\Token;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

$session = new Session();

$id = $_GET['id'] ?? "";
$owner = $_GET['owner'] ?? "";


try {
    if($id === NULL || $owner === NULL)
        throw new \RuntimeException("script error - missing elements");

    if($owner != $session['user']->getNick())
        throw new \RuntimeException("script error - incorrect user");

    $connection = new Connection(include DB_CONFIG);
    $userRepository = new UserRepository($connection);
    $taskRepository = new TaskRepository($connection);

    $userManager = new UserManager($session['user'], $userRepository);
    $userManager->getUserTasks($taskRepository);

    if(!in_array($id, array_map(function($element){
            return $element->getId();
        }, $session['user']->getTaskCollection())
    ))
        throw new \RuntimeException("script error - incorrect task");
}catch (\Exception $e){
    var_dump($e->getMessage());
    die();
}

//-------------------------------------------------------------------------------------
if (!isset($_POST['hidden']))
    $session['token'] = (new Token())->generate()->binToHex()->getToken();

if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include ROOT_PATH . "./templates/editTask.php";
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: ../../templates/errors/404.php");
} else {
        // 0 - remove old errors
    if (isset($session['editErrors']))
        unset($session['editErrors']);

    unset($_POST['submit']);

    $formData['id'] = $id;
    foreach ($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create login logic instance
    $updateTask = new Edit($formData, $connection);

            // create usefully observers
    new ErrorObserver($updateTask);
    new SessionObserver($updateTask);

            // execute create task logic
    if($updateTask->handler($session['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS))
    {
        unset($session['token']);

            // 2 - don't touch task list, only index file manage to download and handle it
            // index.php refresh automatically task list in purpose to always handle lasted version

            // 2 - set header
        header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
    }else
        header("Location: ./editTask.php?id={$id}&owner={$owner}");
}