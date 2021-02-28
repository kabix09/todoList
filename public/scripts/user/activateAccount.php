<?php
require_once __DIR__ . '/../index.php';

use App\Module\Access\UserActions\ActivateAccount;

$activateAccount = new ActivateAccount($session, $connection);
$activateAccount->core();
