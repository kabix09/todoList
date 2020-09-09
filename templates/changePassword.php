<?php
require_once "../vendor/autoload.php";

use App\Form\Factory\Factory;
use App\Token\Token;

define('FORM_CONFIG', "../config/form.config.php");
define('CHANGE_PASSWORD_FORM', "../config/changePasswordForm.config.php");

$formfactory = new Factory();
$formfactory->generate(include CHANGE_PASSWORD_FORM,
                        (new Token($_SESSION['token']))
                            ->hash()
                            ->encode()
                            ->getToken());
?>

<?= $formfactory->render(include FORM_CONFIG, FALSE, TRUE); ?>


