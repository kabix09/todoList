<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\Task\CreateTask;
use App\Module\Access\TaskActions\Create;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

// check guest permission to use the site
$createAccess = new Create($session, $connection);
$createAccess->checkAccess();
$createAccess->core();

// create task form logic: valid & add
$createTask = new CreateTask($session, $connection);
$createTask->generateToken();
$createTask->setTemplatePath(ROOT_PATH . './templates/task/form/createTask.php');
$createTask->setRecaptchaKey((include(RECAPTCHA))["secretKey"]);
$createTask->core();
