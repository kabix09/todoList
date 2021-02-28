<?php
require_once __DIR__ . '/../index.php';

use App\Access\TaskScript\Remove;

$remove = new Remove($session, $connection);
$remove->checkAccess();
$remove->core();
