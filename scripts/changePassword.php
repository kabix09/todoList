<?php
require_once '../init.php';

use App\Module\ErrorObserver;
use App\Module\Password\ChangePwd;
use App\Token\Token;
use App\Connection\Connection;
use App\Repository\UserRepository;
use App\Manager\UserManager;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("CHANGE_PASSWORD_ASSIGNMENTS", ROOT_PATH . './config/changePasswordAssignments.config.php');

if(!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    include ROOT_PATH . './templates/changePassword.php';
    exit();
} elseif($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../templates/errors/404.php");
}else{
        // 0 - remove old errors
    if(isset($_SESSION['changepwdErrors']))
        unset($_SESSION['changepwdErrors']);

    unset($_POST['submit']);

            // remove POST data and operate on local variable
    $formData = array();

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create register logic instance
    $changePwd = new ChangePwd($formData, include DB_CONFIG, $_SESSION['user']);

            // create usefully observers
    new ErrorObserver($changePwd);

            // execute register logic
    if($changePwd->passwordHandler($_SESSION['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include CHANGE_PASSWORD_ASSIGNMENTS))
    {
        unset($_SESSION['token']);

            // 2 - set header
        header("Location: ../index.php");   // TODO - go to info page "password changed successfull" ???
    }else
        header("Location: ./changePassword.php");
}

