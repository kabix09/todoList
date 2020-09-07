<?php
require_once "../vendor/autoload.php";

use App\Form\Factory\Factory;
use App\Token\Token;

define('FORM_CONFIG', "../config/form.config.php");
define('LOG_FORM', "../config/logForm.config.php");


/*
 * $errors = [
 *  "wrong Nickname",
 *  "wrong charset"
 * ];
 * $elementGenerator = new Generic("nick", FormConstants::TYPE_TEXT, "Nick", $wrappers, $attributes, $errors);
 * echo $elementGenerator->render(TRUE);
 */

$formFactory = new Factory();
$formFactory->generate(include LOG_FORM,
                        (new Token($_SESSION['token']))
                            ->hash()
                            ->encode()
                            ->getToken());
?>

<?= $formFactory->render(include FORM_CONFIG, FALSE, TRUE); ?>
