<?php
require_once '../index.php';

use App\Access\TaskScript\Remove;

$remove = new Remove($session, $connection);
$remove->checkAccess();
$remove->core();
