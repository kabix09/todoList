<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

use App\Access\TaskScript\Remove;
use App\Connection\Connection;
use App\Session\Session;

$remove = new Remove(new Session(), new Connection(include DB_CONFIG));

$remove->checkAccess();
$remove->core();
