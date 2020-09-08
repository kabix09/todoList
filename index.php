<?php
require_once './init.php';

if(isset($_SESSION['user']))
{
    echo "<pre>";
    echo "</br><h3>login success !!!</h3></br>";
        var_dump($_SESSION['user']);
    echo "</pre>";
}
else
{
    include $_SESSION['ROOT_PATH'] . './templates/index.php';
}