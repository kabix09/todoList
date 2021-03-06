<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\User\ChangePassword;
use App\Service\Config\{Config, Constants};

$changePassword = new ChangePassword($session, $connection);
$changePassword->generateToken();
$changePassword->setTemplatePath(SITE_ROOT . "./templates/user/form/changePassword.php");
$changePassword->setRecaptchaKey(Config::init()::module(Constants::RECAPTCHA)::get("secretKey")[0]);
$changePassword->core();

