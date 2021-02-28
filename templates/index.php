<!DOCTYPE html>
<html lang="en">
<head>
    <title>Todo List</title>
    <meta name="author" content="kabix09"/>
    <meta name="description" content="Remote task list! With our service you gain access to the list of tasks where and when you want."/>
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/main.css>
    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/list.css>
    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/tasks.css>
    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/modal.css>
</head>
<body>
<header style="text-align: center; border: 2px solid #2196f3; border-top: none; border-radius: 15px;"><h2>My Todo List</h2></header>
    <nav>
        <ul>
            <?php if(!isset($session['user'])):?>
                <li><a href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/scripts/user/login.php>Login</a></li>
                <li><a href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/scripts/user/register.php>Register</a></li>
            <?php else: ?>
                <li><a href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/scripts/user/logout.php>Logout</a></li>
                <li><a href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/scripts/user/changePassword.php>Change password</a></li>
                <input type="text" name="searchBar" id="searchBar" placeholder="search for task"/>
            <?php endif ?>
        </ul>
    </nav>
    <main>
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
            <a href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/scripts/task/create.php>Create Task</a>
        </div>
        <?php endif;?>
        <div id="modalContainer"></div>
    </main>
    <footer>
        @kabix09 2020
    </footer>
    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/js/tasks.js></script>
    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/js/modalCard.js></script>
</body>
</html> 