<?php
require_once '../init.php';

use App\Module\ErrorObserver;
use App\Module\Task\Edit;
use App\Token\Token;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

$id = $_GET['id'] ?? "";
$owner = $_GET['owner'] ?? "";

try {
    if($id === NULL || $owner === NULL)
        throw new \RuntimeException("script error - missing elements");

    if($owner != $_SESSION['user']->getNick())
        throw new \RuntimeException("script error - incorrect user");

    if(!in_array($id, array_map(function($element){
            return $element->getId();
        }, $_SESSION['tasks'])
    ))
        throw new \RuntimeException("script error - incorrect task");
}catch (\Exception $e){
    var_dump($e->getMessage());
    die();
}

//-------------------------------------------------------------------------------------
if (!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include ROOT_PATH . "./templates/editTask.php";
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: ../templates/errors/404.php");
} else {
        // 0 - remove old errors
    if (isset($_SESSION['editErrors']))
        unset($_SESSION['editErrors']);

    unset($_POST['submit']);

    $formData['id'] = $id;
    foreach ($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create login logic instance
    $updateTask = new Edit($formData, include DB_CONFIG);

            // create usefully observers
    new ErrorObserver($updateTask);

            // execute create task logic
    if($updateTask->taskHandler($_SESSION['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS))
    {
        unset($_SESSION['token']);

        // index.php automatically refresh task list stored in session['tasks']
        // next tasks.js read this data and create list view

            // 2 - set header
        header("Location: ../index.php");
    }else
        header("Location: ./editTask.php?id={$id}&owner={$owner}");
}
