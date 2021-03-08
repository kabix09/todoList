<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "./vendor/autoload.php";

use App\Service\Config\{Config, Constants};
use App\Service\Form\Factory\Factory;
use App\Service\Token\Token;

define('FORM_CONFIG', $_SERVER['DOCUMENT_ROOT'] . "./config/form.config.php");
define('REG_FORM', $_SERVER['DOCUMENT_ROOT'] . "./config/regForm.config.php");

/*
 * $errors = [
 *  "wrong Nickname",
 *  "wrong charset"
 * ];
 * $elementGenerator = new Generic("nick", FormConstants::TYPE_TEXT, "Nick", $wrappers, $attributes, $errors);
 * echo $elementGenerator->render(TRUE);
 */

$formfactory = new Factory();
$formfactory->generate(Config::init()::action(Constants::REGISTER)::module(Constants::FORM)::get(),
                        (new Token($this->session['token']))
                            ->hash()
                            ->binToHex()
                            ->getToken());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Form</title>
    <meta name="author" content="kabix09" />
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/form.css>
    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/js-snackbar.css>

    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/js/js-snackbar.js></script>
    <script>
        path = "<?=strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0])?>://<?=$_SERVER['SERVER_NAME']?>:<?=$_SERVER['SERVER_PORT']?>/public/endpoints/variables.php?name=registerErrors";
    </script>
    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/js/formErrors.js></script>

    <?php include_once ($_SERVER['DOCUMENT_ROOT'] . "/templates/recaptchaScript.php"); ?>
</head>
<body style="font-size: 18px;">
    <main style="background-color: ivory;
                    padding: 15px;
                    border-radius: 20px;
                    width: 30rem;
                    position: fixed; top: 40%; left: 50%;
                    transform: translate(-50%, -50%);
                    box-sizing:border-box;
                    -webkit-box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);
                    -moz-box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);
                    box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);">
        <div style="text-align: center; margin: 10px 0 25px 0; font-size: 20px;">
                <span style="border-bottom:  2px solid #000000;">
                    Join to our service
                </span>
        </div>

        <?= $formfactory->render(Config::init()::module(Constants::FORM_TEMPLATE)::get(), FALSE, TRUE); ?>
    </main>
</body>
</html>


