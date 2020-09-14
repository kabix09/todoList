<?php
return $taskForm = [
    "title" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Title",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'titleID',
            "maxLength" => 30,
            'placeholder' => "title",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['taskForm']['title'] ?? NULL
    ],

    "content" => [
        "class" => 'App\Form\Elements\Textarea',
        "type" => App\Form\FormConstants::TEXTAREA,
        "label" => "Description",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'contentID',
            "maxLength" => 35,
            'placeholder' => "content",
            'required' => "",
            'value' => ""
        ],
        "errors" => $_SESSION['taskForm']['content'] ?? NULL
    ],

    "start_date" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATE,
        "label" => "Start date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'startDateID',
            "maxLength" => 35,
            'placeholder' => "startDate",
            'required' => "",
            'value' => (new \DateTime())->format('Y-m-d')
        ],
        "errors" => $_SESSION['taskForm']['startDate'] ?? NULL
    ],

    "target_end_date" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATE,
        "label" => "End date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'endDateID',
            "maxLength" => 35,
            'placeholder' => "endDate"
        ],
        "errors" => $_SESSION['taskForm']['endDate'] ?? NULL
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
        "label" => 'Create',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'submit'
        ]
    ]
];