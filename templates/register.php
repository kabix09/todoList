<?php
require_once "../vendor/autoload.php";

use App\Form\Factory\Factory;
use App\Token\Token;

define('FORM_CONFIG', "../config/form.config.php");
define('REG_FORM', "../config/regForm.config.php");


/*
 * $errors = [
 *  "wrong Nickname",
 *  "wrong charset"
 * ];
 * $elementGenerator = new Generic("nick", FormConstants::TYPE_TEXT, "Nick", $wrappers, $attributes, $errors);
 * echo $elementGenerator->render(TRUE);
 */

$formfactory = new Factory();
$formfactory->generate(include REG_FORM,
                        (new Token($_SESSION['token']))
                            ->hash()
                            ->encode()
                            ->getToken());
?>

<?= $formfactory->render(include FORM_CONFIG, FALSE, TRUE); ?>

