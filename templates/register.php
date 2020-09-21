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

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Form</title>
    <meta name="author" content="kabix09" />
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/js-snackbar.css">

    <script src="../js/js-snackbar.js"></script>
    <script>
        path = "<?=strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0])?>://<?=$_SERVER['SERVER_NAME']?>:<?=$_SERVER['SERVER_PORT']?>/src/JSON/variables.php?name=registerErrors";
    </script>
    <script src="../js/formErrors.js"></script>
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

        <?= $formfactory->render(include FORM_CONFIG, FALSE, TRUE); ?>
    </main>
</body>
</html>


