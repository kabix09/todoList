<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'init.php';

use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Service\Session\Session;

$session = new Session();
$connection = new Connection(Config::init()::module(Constants::DATABASE)::get());