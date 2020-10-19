<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

use App\Access\FormScript\CreateTask;
use App\Connection\Connection;
use App\Session\Session;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

$session = new Session();
$createTask = new CreateTask($session, new Connection(include DB_CONFIG));
$createTask->generateToken();

$createTask->core(ROOT_PATH . './templates/createTask.php');
