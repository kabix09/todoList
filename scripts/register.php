<?php
require_once '../init.php';

use App\Module\ErrorObserver;
use App\Module\Register\Register;
use App\Token\Token;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("REG_ASSIGNMENTS", ROOT_PATH . './config/regAssignments.config.php');

if(!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if(!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include ROOT_PATH . './templates/register.php';
    exit();
}elseif($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: ../templates/errors/404.php");
}else{
        // 0 - remove old errors
    if(isset($_SESSION['registerErrors']))
        unset($_SESSION['registerErrors']);

    unset($_POST['submit']);

            // remove POST data and operate on local variable
    $formData = array();

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create register logic instance
    $register = new Register($formData, include DB_CONFIG);

            // create usefully observers
    new ErrorObserver($register);

            // execute register logic
    if($register->registerHandler($_SESSION['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include REG_ASSIGNMENTS))
    {
        unset($_SESSION['token']);

        $_SESSION['login'] = TRUE;
        $_SESSION['user'] = $register->getUser(TRUE);

            // 2 - set header
        if($_SESSION['user']->getStatus() === 'active')
            header("Location: ../index.php");
        else
            header("Location: ../templates/accountStatus.php");

    }else
        header("Location: ./register.php");

}