<?php
require_once '../init.php';

use App\Token\Token;
use App\Filter\Filter;
use App\Connection\Connection;
use App\Repository\UserRepository;
use App\Manager\UserManager;

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
    if(isset($_SESSION['regForm']))
        unset($_SESSION['regForm']);

    unset($_POST['submit']);

        // 1 - check hidden token
    if(!isset($_SESSION['token']))
        exit("token doesn't exists on server side ://");

    if(sodium_compare(
            (new Token($_SESSION['token']))->hash()->getToken(),
            (new Token($_POST['hidden']))->decode()->getToken()
        ) !== 0
    ) throw new RuntimeException('detected cross-site attack on login form');

    unset($_SESSION['token']);
    unset($_POST['hidden']);

    // remove POST data and operate on local variable
    $formData = array();

    foreach($_POST as $key => $value)
        $formData[$key] = htmlentities($value);

    unset($_POST);

    //-------------------------------------------------------------------------------------

        // 2 - filter data & 3 - valid data
    $filter = new Filter(
        array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include REG_ASSIGNMENTS);
    $filter->process($formData);

    foreach ($filter->getMessages() as $key => $value)
    {
        $_SESSION['regForm'][$key] = $value;
    }

            //  if there is any error message, redirect to login form
    if(isset($_SESSION['regForm'])) {
        header("Location: ./register.php");
        exit();
    }

        // 4 - find user by nick
    $userRepo = new UserRepository(new Connection(include DB_CONFIG));
    $user =  $userRepo->fetchByNick($formData['nick']);

    if( $user )
    {
        $_SESSION['regForm']['nick'] = ['login already exists'];
        header("Location: ./register.php");
        exit();
    }

        // 4 - check if passwords are equals
    if($formData['password'] !== $formData['repeatPassword']) {
        $_SESSION['regForm']['repeatPassword'] = ['passwords must be the same'];
        header("Location: ./register.php");
        exit();
    }

    $userManager = new UserManager($formData,
                        new UserRepository(
                            new Connection(include DB_CONFIG)));

    $userManager->hashPassword($formData['password']);

    $newUser = $userManager->return();

        // 5 - insert
    if($userRepo->insert($newUser))
    {
        $_SESSION['user'] = $userRepo->fetchByNick($newUser->getNick());

            // 6  - header
        if($_SESSION['user']->getStatus() === 'active')
            header("Location: ../index.php");
        else
            header("Location: ../templates/accountStatus.php");
    }else{
        throw new RuntimeException("system error - couldn't create new user :/");
    }
}