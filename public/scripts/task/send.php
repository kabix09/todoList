<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\Task\SendTask;
use App\Module\Access\TaskActions\Send;
use App\Repository\TaskRepository;
use App\Service\Config\{Config, Constants};

// check guest permission to use the site
$sendAccess = new Send($session, $connection);
$sendAccess->checkAccess();
$sendAccess->core();

// catch task
$taskRepository = new TaskRepository($connection);
$task = $taskRepository->fetchById($_GET['id']);

// edit task form logic: valid & send
$sendTask = new SendTask($session, $connection);
$sendTask->generateToken();
$sendTask->setTemplatePath(SITE_ROOT . './templates/task/form/sendTask.php');
$sendTask->setRecaptchaKey(Config::init()::module(Constants::RECAPTCHA)::get("secretKey")[0]);
$sendTask->setTask($task);
$sendTask->core();


