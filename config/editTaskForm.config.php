<?php
use App\Entity\Base;

return $taskForm = [
    "title" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
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
        "class" => 'App\Form\Elements\Textarea',
        "type" => App\Form\FormConstants::TYPE_TEXT,
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
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATETIME,
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
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
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
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
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
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATETIME,
        "label" => "Start date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'startDateID',
            "maxLength" => 35,
            'placeholder' => "startDate",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['startDate'] ?? NULL
    ],

    "target_end_date" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATETIME,
        "label" => "End date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'endDateID',
            "maxLength" => 35,
            'placeholder' => Base::DATE_FORMAT,
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['endDate'] ?? NULL
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
            "value" => 'Update',
            "style" => 'float: right;'
        ]
    ]
];
