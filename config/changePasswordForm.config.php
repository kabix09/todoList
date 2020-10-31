<?php
return $logForm = [
    "password" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_PASSWORD,
        "label" => "New Password",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'passwordID',
            "maxLength" => 35,
            'placeholder' => "password",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['password'] ?? NULL
    ],

    "repeatPassword" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_PASSWORD,
        "label" => "Repeat Password",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'repPasswordID',
            "maxLength" => 35,
            'placeholder' => "password",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['repeatPassword'] ?? NULL
    ],

    "hidden" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_HIDDEN,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => ''
        ]
    ],

    "recaptchaResponse" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_HIDDEN,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'recaptchaResponse',
            "value" => ''
        ]
    ],

    "submit" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_SUBMIT,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'submit',
            "style" => 'float: right; margin-right: 10px;'
        ]
    ]
];