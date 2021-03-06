<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\Task\CreateTask;
use App\Module\Access\TaskActions\Create;
use App\Service\Config\{Config, Constants};

// check guest permission to use the site
$createAccess = new Create($session, $connection);
$createAccess->checkAccess();
$createAccess->core();

// create task form logic: valid & add
$createTask = new CreateTask($session, $connection);
$createTask->generateToken();
$createTask->setTemplatePath(SITE_ROOT . './templates/task/form/createTask.php');
$createTask->setRecaptchaKey(Config::init()::module(Constants::RECAPTCHA)::get("secretKey")[0]);
$createTask->core();
