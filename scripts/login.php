<?php
require_once '../init.php';

use App\Token\Token;

define("FILTER", $_SESSION['ROOT_PATH'] . './config/filter.config.php');
define("LOG_ASSIGNMENTS", $_SESSION['ROOT_PATH'] . './config/logAssignments.config.php');

/*
 * toDo !!!
 * $recaptcha = '';
 */

if(!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if(!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include ROOT_PATH . './templates/login.php';
    exit();
}elseif($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: ../templates/error/404.php");
}else{
        // 0 - remove old errors
    if(isset($_SESSION['logForm']))
        unset($_SESSION['logForm']);

        // 1 - check hidden token
    if(!isset($_SESSION['token']))
        exit("token doesn't exists on server side ://");

    if(sodium_compare(
            (new Token($_SESSION['token']))->hash()->getToken(),
            (new Token($_POST['hidden']))->decode()->getToken()
        ) !== 0
    ) throw new RuntimeException('detected cross-site attack on login form');
    else
        echo "correct Token </br>";

    unset($_SESSION['token']);

            // remove POST data and operate on local variable
    $formData = $_POST;
    unset($_POST);

    //-------------------------------------------------------------------------------------

        // 2 - filter data
    $filter = new App\Filter\Filter((include FILTER)['filters'], include LOG_ASSIGNMENTS);
    $filter->process($formData);

    foreach ($filter->getMessages() as $key => $value)
    {
        $_SESSION['logForm'][$key] = $value;
    }

            //  if there is any error message, redirect to login form
    if(isset($_SESSION['logForm'])) {
        header("Location: ./login.php");
    }else
        {
        echo '<pre>';
            var_dump($filter->getItemsAsArray());
        echo '</pre>';
    }

    /*
     * toDO !!!
     * 3 - valid data
     * 4 - check password
     * 5 - header
     */
}






