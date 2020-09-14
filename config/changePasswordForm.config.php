<?php
return $logForm = [
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
        "errors" => $_SESSION['changePwdForm']['password'] ?? NULL
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
        "errors" => $_SESSION['changePwdForm']['repeatPassword'] ?? NULL
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