<?php
require_once '../init.php';

/*
 * $recaptcha = '';
 */

if(!isset($_POST['hidden']))
    $_SESSION['token'] = bin2hex(random_bytes(16));

if(!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] !== 'POST')
{
    include '../templates/login.php';
}
else {
    unset($_POST['submit']);

    echo '<pre>';
        var_dump($_POST);
    echo '</pre>';

    if(!isset($_SESSION['token']))
        exit("token doesn't exists on server side ://");

    if($_SESSION['token'] !== $_POST['hidden'])
        throw new RuntimeException('detected cross-site attack on login form');
    else
        echo "token correct";

    unset($_SESSION['token']);

    //-------------------------------------------------------------------------------------

    // 2 - filter data

    // 3 - valid data

    // check password

    // insert into db

    // header
}



