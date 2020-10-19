<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

use App\Access\Access;
use App\Entity\Mapper\TaskMapper;
use App\Form\Factory\Factory;
use App\Session\Session;
use App\Token\Token;

define('FORM_CONFIG', ROOT_PATH . "./config/form.config.php");
define('TASK_FORM', ROOT_PATH . "./config/editTaskForm.config.php");

$session = new Session();
$taskConfig = include TASK_FORM;
$editedTask = NULL;

foreach ($session['user']->getTaskCollection() as $task)
{
    if($task->getId() == $_GET['id'])
    {
        $editedTask = $task;
    }
}

$editedTask = TaskMapper::entityToArray($editedTask);

foreach ($taskConfig as $element => &$values)
{
    if($element !== 'hidden' && $element !== 'submit')
    {
        $values["attributes"]["value"] = $editedTask[$element];
    }
}

$formFactory = new Factory();
$formFactory->generate($taskConfig,
    (new Token($session['token']))
        ->hash()
        ->encode()
        ->getToken());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Task Form</title>
    <meta name="author" content="kabix09" />
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/style/form.css>
    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/style/js-snackbar.css>

    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/js/js-snackbar.js></script>
    <script>
        path = "<?=strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0])?>://<?=$_SERVER['SERVER_NAME']?>:<?=$_SERVER['SERVER_PORT']?>/src/JSON/variables.php?name=editErrors";
    </script>
    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/js/formErrors.js></script>
</head>
<body style="font-size: 18px;">
<main style="background-color: ivory;
                padding: 15px;
                border-radius: 20px;
                width: 26rem;
                position: fixed; top: 40%; left: 50%;
                transform: translate(-50%, -40%);
                box-sizing:border-box;
                -webkit-box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);
                box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);">
    <div style="text-align: center; margin: 10px 0 25px 0; font-size: 20px;">
            <span style="border-bottom:  2px solid #000000;">
                Edit Task
            </span>
    </div>

    <?= $formFactory->render(include FORM_CONFIG, FALSE, TRUE)?>
</main>
</body>
</html>
