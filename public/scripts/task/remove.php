<?php
require_once __DIR__ . '/../index.php';

use App\Module\Access\TaskActions\Remove;

$remove = new Remove($session, $connection);
$remove->checkAccess();
$remove->core();
