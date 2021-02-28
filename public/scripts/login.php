<?php
require_once __DIR__ . './index.php';

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("LOG_ASSIGNMENTS", ROOT_PATH . './config/logAssignments.config.php');

use App\Access\FormScript\User\Login;

$login = new Login($session, $connection);
$login->generateToken();
$login->setTemplatePath(ROOT_PATH . "./templates/user/form/login.php");
$login->setRecaptchaKey((include(RECAPTCHA))["secretKey"]);
$login->core();
