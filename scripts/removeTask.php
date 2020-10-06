<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Logger\MessageSheme;
use App\Repository\TaskRepository;
use App\Session\Session;
use App\Session\SessionManager;

$session = new Session();

if(!isset($session['user']) && !isset($session['tasks']))
{
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? NULL;
$owner = $_GET['owner'] ?? NULL;

$logger = new \App\Logger\Logger();

try {
    if($id === NULL || $owner === NULL)
        throw new RuntimeException("script error - missing elements");

    if($owner != $session['user']->getNick())
        throw new RuntimeException("script error - incorrect user");

    if(!in_array($id, array_map(function($element){
                                    return $element->getId();
                                }, $session['tasks'])
    ))
        throw new RuntimeException("script error - incorrect task");

    $sessionManager = new SessionManager($session);
    if(!$sessionManager->manage())
    {
        // logout and redirect to login page
        die("toDo - user error in remove task script");
    }

    $taskRepository = new TaskRepository(new Connection(include DB_CONFIG));
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
        header("Location: ../index.php");
    }else{
        throw new RuntimeException("An attempt to remove task with id: {$id} has failed");
    }

}catch (\Exception $e){
    $config = new MessageSheme($session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
    $logger->error($e->getMessage(), [$config]);
    die();
}



