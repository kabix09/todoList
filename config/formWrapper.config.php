<?php
return $wrappers = [
    App\Service\Form\FormConstants::WRAPPER => ['type' => 'div'],
    App\Service\Form\FormConstants::INPUT => ['type' => 'input', 'class' => 'content'],
    App\Service\Form\FormConstants::LABEL => ['type' => 'label', 'for' => NULL],
    App\Service\Form\FormConstants::ERRORS => ['type' => 'div', 'calss' => 'error']
];