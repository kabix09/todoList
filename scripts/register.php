<?php
require_once './index.php';

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("REG_ASSIGNMENTS", ROOT_PATH . './config/regAssignments.config.php');

use App\Access\FormScript\User\Register;

$register = new Register($session, $connection);
$register->generateToken();
$register->setTemplatePath(ROOT_PATH . "./templates/register.php");
$register->core();
