<?php
namespace App\Form\Elements;

use App\Form\Generic;
use App\Form\FormConstants;

class Textarea extends Generic
{
    protected string $pattern = '<textarea name="%s" %s></textarea>';

    public function __construct($name, $type, $label = '', array $wrappers = array(), array $attributes = array(), array $errors = array())
    {
        if(!empty($wrappers)){
            $wrappers['input']['type'] = FormConstants::TEXTAREA;
        }

        parent::__construct($name, $type, $label, $wrappers, $attributes, $errors);
    }

    protected function getTextarea()
    {
        return sprintf($this->pattern,
            $this->name,
            $this->getAttributes()
        ) . PHP_EOL;
    }

    public function getInput(): string
    {
        return $this->getTextarea();
    }
}