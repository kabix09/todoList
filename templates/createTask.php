<?php
require_once '../vendor/autoload.php';

use App\Form\Factory\Factory;
use App\Token\Token;

define('FORM_CONFIG', "../config/form.config.php");
define('TASK_FORM', "../config/taskForm.config.php");

$formFactory = new Factory();
$formFactory->generate(include TASK_FORM,
                        (new Token($_SESSION['token']))
                            ->hash()
                            ->encode()
                            ->getToken());
?>

<?= $formFactory->render(include FORM_CONFIG, FALSE, TRUE)?>
