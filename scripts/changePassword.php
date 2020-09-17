<?php
require_once '../init.php';

use App\Token\Token;
use App\Filter\Filter;
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
    if(isset($_SESSION['formErrors']))
        unset($_SESSION['formErrors']);

        // 1 - check hidden token
    if(!isset($_SESSION['token']))
        exit("token doesn't exists on server side ://");

    if(sodium_compare(
            (new Token($_SESSION['token']))->hash()->getToken(),
            (new Token($_POST['hidden']))->decode()->getToken()
        ) !== 0
    ) throw new RuntimeException('detected cross-site attack on login form');

    unset($_SESSION['token']);

    // remove POST data and operate on local variable
    $formData = array();

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

    //-------------------------------------------------------------------------------------

        // 2 - filter data & 3 - valid data
    $filter = new Filter(
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include CHANGE_PASSWORD_ASSIGNMENTS);
    $filter->process($formData);

    foreach ($filter->getMessages() as $key => $value)
    {
        $_SESSION['changePwdForm'][$key] = $value;
    }

        //  if there is any error message, redirect to login form
    if(isset($_SESSION['formErrors'])) {
        header("Location: ./changePassword.php");
        exit();
    }

        // 4 - check if passwords are equals
    if($formData['password'] !== $formData['repeatPassword']) {
        $_SESSION['changePwdForm']['repeatPassword'] = ['passwords must be the same'];
        header("Location: ./changePassword.php");
        exit();
    }

        // 5 - change password
    $userManager = new UserManager(NULL,
                        new UserRepository(
                            new Connection(include DB_CONFIG)));

    if($userManager->changePassword($_SESSION['user'], $formData['password']))
        header("Location: ../index.php");   // TODO - go to info page "password changed successfull" ???
    else
        throw new RuntimeException("system error - the password could not be changed");
}

