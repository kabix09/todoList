<?php
return $regForm = [
    "nick" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Set Nick",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'nickID',
            "maxLength" => 30,
            'placeholder' => "nickname",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['regForm']['nick'] ?? NULL
    ],

    "email" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_EMAIL,
        "label" => "Set Email",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'emailID',
            "maxLength" => 30,
            'placeholder' => "email",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['regForm']['email'] ?? NULL
    ],

    "password" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_PASSWORD,
        "label" => "Set Password",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'passwordID',
            "maxLength" => 35,
            'placeholder' => "password",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['regForm']['password'] ?? NULL
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
        ],
        "errors" => $_SESSION['regForm']['repeatPassword'] ?? NULL
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

    "submit" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_SUBMIT,
        "label" => 'Register',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'submit'
        ]
    ]
];