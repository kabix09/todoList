<?php
require_once __DIR__ . '/../index.php';

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("CHANGE_PASSWORD_ASSIGNMENTS", ROOT_PATH . './config/changePasswordAssignments.config.php');

use App\Module\FormActions\User\ChangePassword;

$changePassword = new ChangePassword($session, $connection);
$changePassword->generateToken();
$changePassword->setTemplatePath(ROOT_PATH . "./templates/user/form/changePassword.php");
$changePassword->setRecaptchaKey((include(RECAPTCHA))["secretKey"]);
$changePassword->core();

