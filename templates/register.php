<?php
require_once "../vendor/autoload.php";

define('FORM_WRAPPER', "../config/formWrapper.config.php");
define('FORM_CONFIG', "../config/form.config.php");
define('REG_FORM', "../config/regForm.config.php");

use App\Form\Factory\Factory;

/*
 * $errors = [
 *  "wrong Nickname",
 *  "wrong charset"
 * ];
 * $elementGenerator = new Generic("nick", FormConstants::TYPE_TEXT, "Nick", $wrappers, $attributes, $errors);
 * echo $elementGenerator->render(TRUE);
 */

$formfactory = new Factory();
$formfactory->generate(include REG_FORM);
?>

<?= $formfactory->render(include FORM_CONFIG, FALSE, TRUE); ?>

