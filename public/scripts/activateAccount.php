<?php
require_once __DIR__ . './index.php';

use App\Access\UserScript\ActivateAccount;

$activateAccount = new ActivateAccount($session, $connection);
$activateAccount->core();
