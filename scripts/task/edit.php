<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

use App\Access\FormScript\EditTask;
use App\Access\TaskScript\Edit;
use App\Connection\Connection;
use App\Session\Session;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

$session = new Session();
$connection = new Connection(include DB_CONFIG);

// check guest permission to use the site
$editAccess = new Edit($session, $connection);
$editAccess->checkAccess();
$editAccess->core();

// edit task form logic: valid & add
$editTask = new EditTask($session, $connection);
$editTask->generateToken();
$editTask->core(ROOT_PATH . './templates/editTask.php');
