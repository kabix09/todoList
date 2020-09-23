<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Password\ChangePwd;
use App\Module\SessionObserver;
use App\Session\Session;
use App\Token\Token;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("CHANGE_PASSWORD_ASSIGNMENTS", ROOT_PATH . './config/changePasswordAssignments.config.php');

$session = new Session();

if(!isset($_POST['hidden']))
    $session['token'] = (new Token())->generate()->binToHex()->getToken();

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    include ROOT_PATH . './templates/changePassword.php';
    exit();
} elseif($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../templates/errors/404.php");
}else{
        // 0 - remove old errors
    if(isset($session['changepwdErrors']))
        unset($session['changepwdErrors']);

    unset($_POST['submit']);

            // remove POST data and operate on local variable
    $formData = array();

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create register logic instance
    $changePwd = new ChangePwd($formData, new Connection(include DB_CONFIG), $session['user']);

            // create usefully observers
    new ErrorObserver($changePwd);
    new SessionObserver($changePwd);

            // execute register logic
    if($changePwd->handler($session['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include CHANGE_PASSWORD_ASSIGNMENTS))
    {
        unset($session['token']);

            // 2 - set header
        header("Location: ../index.php");   // TODO - go to info page "password changed successfull" ???
    }else
        header("Location: ./changePassword.php");
}

