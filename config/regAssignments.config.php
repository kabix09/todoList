<?php

return $regAssignments = [
    '*' => [
        ['key' => 'trim', 'params' => []],
        ['key' => 'htmlentities', 'params' => ['ENT_QUOTES']]
    ],
    'nick' => [
        ['key' => 'length', 'params' => ['length' => 30]]
    ],
    'email' => [
        ['key' => 'length', 'params' => ['length' => 35]],
        ['key' => 'email_sanitize', 'params' => []]
    ],
    'password' => [
        ['key' => 'length', 'params' => ['min' => 8, 'max' => 35]]
    ],
    'repeatPassword' => [
        ['key' => 'length', 'params' => ['min' => 8, 'max' => 35]]
    ]
];