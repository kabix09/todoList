<?php
require_once __DIR__ . '/../index.php';

use App\Access\FormScript\Task\EditTask;
use App\Access\TaskScript\Edit;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

// check guest permission to use the site
$editAccess = new Edit($session, $connection);
$editAccess->checkAccess();
$editAccess->core();

// edit task form logic: valid & add
$editTask = new EditTask($session, $connection);
$editTask->generateToken();
$editTask->setTemplatePath(ROOT_PATH . './templates/task/form/editTask.php');
$editTask->setRecaptchaKey((include(RECAPTCHA))["secretKey"]);
$editTask->setTaskID($_GET['id']);
$editTask->core();
