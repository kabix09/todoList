<?php

use App\Service\Form\Generic;
use App\Entity\Base;

return $taskForm = [
    "title" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TEXT,
        "label" => "Title",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'titleID',
            "maxLength" => 30,
            'value' => "",
            'readonly'=>""
        ]
        //"errors" => $_SESSION['formErrors']['title'] ?? NULL
    ],

    "content" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TEXT,
        "label" => "Description",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'contentID',
            "maxLength" => 255,
            'placeholder' => "content",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['content'] ?? NULL
    ],

    "create_date" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_DATETIME,
        "label" => "Create date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'createDateID',
            "maxLength" => 35,
            'value' => "",
            'readonly' => ""
        ]
    ],

    "author" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TEXT,
        "label" => "Author",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'authorID',
            "maxLength" => 30,
            'value' => "",
            'readonly'=>""
        ]
    ],

    "owner" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TEXT,
        "label" => "Owner",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'ownerID',
            "maxLength" => 30,
            'value' => "",
            'readonly'=>""
        ]
    ],

    "start_date" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_DATE,
        "label" => "Start date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'startDateID',
            "maxLength" => 35,
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['startDate'] ?? NULL
    ],

    "start_time" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TIME,
        "label" => "Start time",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'startTimeID',
            "maxLength" => 35,
            'required' => "",
            'value' => (new \DateTime())->format('H:i')
        ]
    ],

    "target_end_date" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_DATE,
        "label" => "End date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'endDateID',
            "maxLength" => 35
        ]
        //"errors" => $_SESSION['formErrors']['endDate'] ?? NULL
    ],

    "end_time" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_TIME,
        "label" => "End time",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'endTimeID',
            "maxLength" => 35,
            'value' => (new \DateTime())->format('H:i')
        ]
        //"errors" => $_SESSION['formErrors']['startDate'] ?? NULL
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

    "submit" => [
        "class" => Generic::class,
        "type" => App\Service\Form\FormConstants::TYPE_SUBMIT,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'Update',
            "style" => 'float: right;'
        ]
    ]
];
