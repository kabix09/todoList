<?php
return $logForm = [
    "nick" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Get Nick",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'nickID',
            "maxLength" => 30,
            'placeholder' => "nickname",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['logForm']['nick'] ?? NULL
    ],

    "password" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_PASSWORD,
        "label" => "Get Password",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'passwordID',
            "maxLength" => 35,
            'placeholder' => "password",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['logForm']['password'] ?? NULL
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
        "label" => 'LogIn',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'submit'
        ]
    ]
];