<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

use App\Access\TaskScript\ChangeStatus;
use App\Connection\Connection;
use App\Session\Session;

$newStatus = new ChangeStatus(new Session(), new Connection(include DB_CONFIG));

$newStatus->checkAccess();
$newStatus->core();