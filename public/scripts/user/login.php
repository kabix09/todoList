<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\User\Login;
use App\Service\Config\{Config, Constants};

$login = new Login($session, $connection);
$login->generateToken();
$login->setTemplatePath(SITE_ROOT . "./templates/user/form/login.php");
$login->setRecaptchaKey(Config::init()::module(Constants::RECAPTCHA)::get("secretKey")[0]);
$login->core();
