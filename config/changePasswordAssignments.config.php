<?php

return $changePasswordAssignments = [
    '*' => [
        ['key' => 'trim', 'params' => []],
        ['key' => 'htmlentities', 'params' => ['ENT_QUOTES']]
    ],
    'password' => [
        ['key' => 'length', 'params' => ['min' => 8, 'max' => 35]]
    ]
];