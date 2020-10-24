<?php
require_once './index.php';

use App\Access\UserScript\ActivateAccount;

$activateAccount = new ActivateAccount($session, $connection);
$activateAccount->core();
