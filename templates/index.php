<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
</head>

<body>
    <li>
        <?php if(!isset($_SESSION['user'])):?>
            <lu><a href="./scripts/login.php" >Login</a></lu>
            <lu><a href="./scripts/register.php" >Register</a></lu>
        <?php else: ?>
            <lu><a href="./scripts/logout.php" >Logout</a></lu>
        <?php endif ?>
    </li>


    <h4> Hello
        <?php printf( isset($_SESSION['user']) ?
            $_SESSION['user']->getNick() :
            "world :))");
        ?>
    </h4><br>

</body>

</html> 