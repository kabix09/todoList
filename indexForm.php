<?php
require_once "./vendor/autoload.php";

use App\Form\ {Generic, FormConstants};

$wrappers = [
    FormConstants::WRAPPER => ['type' => 'div'],
    FormConstants::INPUT => ['type' => 'input', 'class' => 'content'],
    FormConstants::LABEL => ['type' => 'label', 'for' => NULL],
    FormConstants::ERRORS => ['type' => 'div', 'calss' => 'error']
];

$attributes = [
    "id" => "nicko",
    "class" => "inputClass",
    "placeholder" => "getNick",
    "required" => NULL
];

$errors = [
    "wrong Nickname",
    "wrong charset"
];

$elementGenerator = new Generic("nick", FormConstants::TYPE_TEXT, "Nick", $wrappers, $attributes, $errors);

echo $elementGenerator->render(TRUE);