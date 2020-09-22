<!DOCTYPE html>
<html lang="en">
<head>
    <title>Todo List</title>
    <meta name="author" content="kabix09"/>
    <meta name="description" content="Remote task list! With our service you gain access to the list of tasks where and when you want."/>
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./style/list.css">
    <link rel="stylesheet" href="./style/tasks.css">
</head>

<body>
<header style="text-align: center; border: 2px solid #2196f3; border-top: none; border-radius: 15px;"><h2>My Todo List</h2></header>
    <nav>
        <ul>
            <?php if(!isset($session['user'])):?>
                <li><a href="./scripts/login.php">Login</a></li>
                <li><a href="./scripts/register.php">Register</a></li>
            <?php else: ?>
                <li><a href="./scripts/logout.php">Logout</a></li>
                <li><a href="./scripts/changePassword.php">Change password</a></li>
                <input type="text" name="searchBar" id="searchBar" placeholder="search for task"/>
            <?php endif ?>
        </ul>
    </nav>
    <main style="background-color: #c4dfef; padding: 10px; border-radius: 10px 10px 20px 20px;text-align: center;">
        <h3> Hello
            <?php printf( isset($session['user']) ?
                $session['user']->getNick() :
                "world :))");
            ?>
        </h3>

        <?php if(isset($session['user'])): ?>
        <section id="mainBlock"
             style="display:flex; flex-direction: row; flex-wrap: wrap; justify-content: space-evenly; text-align: left;" >

        </section>
        <div style="padding: 10px;">
            <a href="./scripts/createTask.php">Create Task</a>
        </div>
        <?php endif;?>
    </main>
    <footer style="padding: 10px 15px 10px 15px; background-color: white; color: gray">
        @kabix09 2020
    </footer>
    <script src="../js/tasks.js"></script>
</body>

</html> 