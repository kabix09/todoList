<?php
require_once '../init.php';

use App\Token\Token;

/*
 * $recaptcha = '';
 */

if(!isset($_POST['hidden']))
    $_SESSION['token'] = (new Token())->generate()->binToHex()->getToken();

if(!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include ROOT_PATH . './templates/login.php';
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: {$_SESSION['ROOT_PATH']} index.php");
}

echo '<pre>';
    var_dump($_POST);
echo '</pre>';

unset($_POST['submit']);

// 1 check hidden token
if(!isset($_SESSION['token']))
    exit("token doesn't exists on server side ://");

if(sodium_compare(
        (new token($_SESSION['token']))->hash()->getToken(),
        (new Token($_POST['hidden']))->decode()->getToken()
    ) !== 0
) throw new RuntimeException('detected cross-site attack on login form');

unset($_SESSION['token']);

echo "correct Token";
    //-------------------------------------------------------------------------------------

    // 2 - filter data

    // 3 - valid data

    // check password

    // insert into db

    // header



