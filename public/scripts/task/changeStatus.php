<?php
require_once __DIR__ . '/../index.php';

use App\Access\TaskScript\ChangeStatus;

$newStatus = new ChangeStatus($session, $connection);
$newStatus->checkAccess();
$newStatus->core();