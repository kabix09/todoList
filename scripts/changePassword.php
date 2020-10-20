<?php
require_once './index.php';

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("CHANGE_PASSWORD_ASSIGNMENTS", ROOT_PATH . './config/changePasswordAssignments.config.php');

use App\Access\FormScript\User\ChangePassword;

$changePassword = new ChangePassword($session, $connection);
$changePassword->generateToken();
$changePassword->setTemplatePath(ROOT_PATH . "./templates/changePassword.php");
$changePassword->core();

