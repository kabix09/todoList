<?php
require_once __DIR__ . '/../index.php';

use App\Module\Access\TaskActions\ChangeStatus;

$newStatus = new ChangeStatus($session, $connection);
$newStatus->checkAccess();
$newStatus->core();