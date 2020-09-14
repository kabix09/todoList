<?php
session_start();
if(isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    unset($_SESSION['tasks']);
}
session_destroy();
header("Location: ../index.php");