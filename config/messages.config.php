<?php
use App\Service\Filter\Elements\Messages;

Messages::setMessages(
    [
        'length_too_short' => 'Length must be at least %d',
        'length_too_long' => 'Length must be no more than %d',
        'required' => 'Please be sure to enter a value',
        'alnum' => 'Only letters and numbers allowed',
        'float' => 'Only numbers or decimal point',
        'email' => 'Invalid email address',
        'in_array' => 'Not found in the list',
        'trim' => 'Item was trimmed',
        'strip_tags' => 'Tags were removed from this item',
        'filter_float' => 'Converted to a decimal number',
        'phone' => 'Phone number is [+n] nnn-nnn-nnnn',
        'filter_length' => 'Reduced to specified length',
        'htmlentities' => 'Converted characters to HTML entities'
    ]
);