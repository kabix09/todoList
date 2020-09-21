<?php
require_once '../init.php';

use App\Module\Login\Login;
use App\Module\Login\Observers\DateObserver;
use App\Module\ErrorObserver;
use App\Token\Token;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("LOG_ASSIGNMENTS", ROOT_PATH . './config/logAssignments.config.php');

if(!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if(!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include ROOT_PATH . './templates/login.php';
    exit();
}elseif($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: ../templates/errors/404.php");
}else{
        // 0 - remove old errors
    if(isset($_SESSION['loginErrors']))
        unset($_SESSION['loginErrors']);

    unset($_POST['submit']);

        // remove POST data and operate on local variable
    $formData = array();

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

        // 1 - create login logic instance
    $login = new Login($formData, include DB_CONFIG);

            // create usefully observers
    new ErrorObserver($login);
    new DateObserver($login);

            // execute login logic
    if($login->loginHandler($_SESSION['token'],
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include LOG_ASSIGNMENTS))
    {
        unset($_SESSION['token']);

        $_SESSION['login'] = TRUE;
        $_SESSION['user'] = $login->getUser();

        if($_SESSION['user']->getStatus() === 'active')
            header("Location: ../index.php");
        else
            header("Location: ../templates/accountStatus.php");
    }else
        header("Location: ./login.php");
        //throw new RuntimeException('unexpected error...');

}