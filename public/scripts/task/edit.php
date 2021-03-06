<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\Task\EditTask;
use App\Module\Access\TaskActions\Edit;
use App\Service\Config\{Config, Constants};

// check guest permission to use the site
$editAccess = new Edit($session, $connection);
$editAccess->checkAccess();
$editAccess->core();

// edit task form logic: valid & add
$editTask = new EditTask($session, $connection);
$editTask->generateToken();
$editTask->setTemplatePath(SITE_ROOT . './templates/task/form/editTask.php');
$editTask->setRecaptchaKey(Config::init()::module(Constants::RECAPTCHA)::get("secretKey")[0]);
$editTask->setTaskID($_GET['id']);
$editTask->core();
