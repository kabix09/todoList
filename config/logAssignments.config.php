<?php

return $logAssignments = [
    '*' => [
        ['key' => 'trim', 'params' => []],
        ['key' => 'strip_tags', 'params' => []]
    ],
    'nick' => [
        ['key' => 'length', 'params' => ['length' => 30]]
    ],
    'password' => [
        ['key' => 'length', 'params' => ['min' => 8, 'max' => 35]]
    ]
];