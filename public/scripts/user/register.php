<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\User\Register;
use App\Service\Config\{Config, Constants};

$register = new Register($session, $connection);
$register->generateToken();
$register->setTemplatePath(SITE_ROOT . "./templates/user/form/register.php");
$register->setRecaptchaKey(Config::init()::module(Constants::RECAPTCHA)::get("secretKey")[0]);
$register->core();
