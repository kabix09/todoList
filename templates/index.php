<!DOCTYPE html>
<html lang="en">
<head>
    <title>Todo List</title>
    <meta charset="utf-8"/>
    <meta >
</head>

<body style="">
    <header> My Todo List </header>
    <nav>
        <ul>
            <?php if(!isset($_SESSION['user'])):?>
                <li><a href="./scripts/login.php">Login</a></li>
                <li><a href="./scripts/register.php">Register</a></li>
            <?php else: ?>
                <li><a href="./scripts/logout.php">Logout</a></li>
                <li><a href="./scripts/changePassword.php">Change password</a></li>
            <?php endif ?>
        </ul>
    </nav>
    <main>
        <h4> Hello
            <?php printf( isset($_SESSION['user']) ?
                $_SESSION['user']->getNick() :
                "world :))");
            ?>
        </h4>

        <?php if(isset($_SESSION['user'])): ?>
        <div id="mainBlock">
            <?php if(isset($_SESSION['task'])):
                foreach ($_SESSION['task'] as $task): ?>
                <div class="card" style="height: 100px; width: 60px;">
                    <span><?= $task->getName(); ?></span>
                    <span><?= $task->getAuthor(); ?></span>
                    <div><a href="">remove</a></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <h3>You already haven't any task!!!</h3>
            <?php endif; ?>
        </div>
        <div>
            <a href="">Create Task</a>
        </div>
        <?php endif; ?>
    </main>
    <footer>
        @kabix09 2020
    </footer>
</body>

</html> 