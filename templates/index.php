<!DOCTYPE html>
<html lang="en">
<head>
    <title>Todo List</title>
    <meta charset="utf-8"/>
    <meta >
</head>

<body style="">
<header style="text-align: center; border-bottom: 1px solid black"><h2>My Todo List</h2></header>
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
    <main style="background-color: aliceblue; padding: 10px; text-align: center;">
        <h3> Hello
            <?php printf( isset($_SESSION['user']) ?
                $_SESSION['user']->getNick() :
                "world :))");
            ?>
        </h3>

        <?php if(isset($_SESSION['user'])): ?>
        <section id="mainBlock"
             style="display:flex; flex-direction: row; flex-wrap: wrap; justify-content: space-evenly; background-color: azure; text-align: left;">
            <?php if(isset($_SESSION['tasks'])):
                foreach ($_SESSION['tasks'] as $task): ?>
                <div class="card" style="height: 100px; width: 300px; margin: 5px; padding:10px; background-color: #97d1cb; border-radius: 15px; float:left;">
                    <span><b>Title:</b> <?= $task->getTitle(); ?></span><br>
                    <span><b>Author:</b> <?= $task->getAuthor(); ?></span><br>
                    <span><b>Content:</b> <?= $task->getContent(); ?></span><br><br>
                    <div><a href="./scripts/removeTask.php?id=<?= $task->getId(); ?>&owner=<?= $task->getOwner()?>">remove</a></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <h3 style="clear: both">You already haven't any task!!!</h3>
            <?php endif; ?>
        </section>
        <div style="padding: 10px;">
            <a href="./scripts/createTask.php">Create Task</a>
        </div>
        <?php endif; ?>
    </main>
    <footer
        style="padding: 10px 15px 10px 15px; background-color: white; color: gray">
        @kabix09 2020
    </footer>
</body>

</html> 