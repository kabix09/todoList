<?php

use App\Service\Form\Generic;

return $taskForm = [
    "new_owner" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TEXT,
        "label" => "Send To",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'sendToID',
            "maxLength" => 35,
            'value' => ""
        ]
    ],

    "recaptchaResponse" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_HIDDEN,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'recaptchaResponse',
            "value" => ''
        ]
    ],

    "hidden" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_HIDDEN,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => ''
        ]
    ],

    "submit" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_SUBMIT,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'Send',
            "style" => 'float: right; margin-right: 10px;'
        ]
    ]
];