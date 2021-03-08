<?php
require_once $_SERVER['DOCUMENT_ROOT'] . './init.php';

use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Service\Session\Session;

$session = new Session();
$connection = new Connection(Config::init()::module(Constants::DATABASE)::get());