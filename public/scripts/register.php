<?php
require_once __DIR__ . './index.php';

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("REG_ASSIGNMENTS", ROOT_PATH . './config/regAssignments.config.php');

use App\Module\FormActions\User\Register;

$register = new Register($session, $connection);
$register->generateToken();
$register->setTemplatePath(ROOT_PATH . "./templates/user/form/register.php");
$register->setRecaptchaKey((include(RECAPTCHA))["secretKey"]);
$register->core();
