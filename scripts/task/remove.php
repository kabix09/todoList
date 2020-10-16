<?php
require_once '../../init.php';

use App\Connection\Connection;
use App\Logger\MessageSheme;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Session\SessionManager;

$session = new Session();

if(!isset($session['user']))
{
    header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
    exit();
}

$id = $_GET['id'] ?? NULL;
$owner = $_GET['owner'] ?? NULL;

$logger = new \App\Logger\Logger();

try {
    // blocked - an attempt to call a script without no parameters
    if($id === NULL || $owner === NULL)
        throw new RuntimeException("invalid request - missing parameters: id or owner task");

    // blocked - an attempt to call a script with random parameters eg user nick, task id
    if($owner != $session['user']->getNick())
        throw new RuntimeException("incorrect user - trying to delete another user's task");

    // prepare tools
    $connection = new Connection(include DB_CONFIG);
    $userRepository = new UserRepository($connection);
    $taskRepository = new TaskRepository($connection);

    $userManager = new UserManager($session['user'], $userRepository);

    if(empty($session['user']->getTaskCollection()))
        $userManager->getUserTasks($taskRepository);

    if(!in_array($id, array_map(function($element){
                return $element->getId();
            }, $session['user']->getTaskCollection())
    ))
        throw new RuntimeException("incorrect task - this user has no such task");

    // session operation - when we have sure that request is correct
    $sessionManager = new SessionManager($session);
    if(!$sessionManager->manage())
    {
        // logout and redirect to login page
        die("toDo - user error in remove task script");
    }

    // main operation
    if($taskRepository->remove(
        [
            "WHERE" =>NULL,
            "AND" => ["id = '{$id}'", "owner = '{$owner}'"]
        ]))
    {
        // log event
        $config = new MessageSheme($session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
        $logger->info("Successfully removed task with id: {$id}", [$config]);
            // no need to remove from session because
            // index.php automatically refresh task list
        header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
    }else{
        throw new RuntimeException("An attempt to remove task with id: {$id} has failed");
    }

}catch (\Exception $e){
    $config = new MessageSheme($session['user']->getNick(),
        empty(__CLASS__)  ? "task" : __CLASS__,
        empty(__FUNCTION__) ? "remove" : __FUNCTION__,
        TRUE);
    $logger->error($e->getMessage(), [$config]);
    die();
}



