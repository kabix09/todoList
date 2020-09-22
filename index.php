<?php
require_once './init.php';

use App\Connection\Connection;
use App\Repository\TaskRepository;
use App\Session\Session;

$session = new Session();
/*
$sesManager = new SessionManager($session);
if(!$sesManager->manage())
{
    throw new \ErrorException("incompatible browser data");
}
var_dump($session['counter']);
*/

    // chceck account status in purpose to redirect
if(isset($session["user"]) && $session["user"]->getStatus() !== "active"){
    header("Location: ./templates/accountStatus.php");
    exit();
}

    // remove form errors handled in session
$sessionKeys = array_keys($session->getSession());
foreach ($sessionKeys as $key)
{
    if(preg_match("/Errors/", $key))
    {
        unset($session[$key]);
    }
}

    // refresh task list if user is logged
if(isset($session["user"]))   //  && !isset($_SESSION['tasks'])
{
        // if exists remove old tasks
    if(isset($session["tasks"]))
        unset($session["tasks"]);

        // download all tasks
    $taskRepo = new TaskRepository(new Connection(include DB_CONFIG));
    $tasks = $taskRepo->find(array(), [
        "WHERE" => ["owner", "= '{$session["user"]->getNick()}'"]
    ]);

    foreach ($tasks as $task)
        $session["tasks"] = array_merge($session["tasks"] ?? array(), [$task]);

}
    // main page
include ROOT_PATH . './templates/index.php';
