<?php
return $taskForm = [
    "new_owner" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Send To",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'sendToID',
            "maxLength" => 35,
            'value' => ""
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
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'Send',
            "style" => 'float: right; margin-right: 10px;'
        ]
    ]
];