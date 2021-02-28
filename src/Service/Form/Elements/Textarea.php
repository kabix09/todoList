<?php
namespace App\Service\Form\Elements;

use App\Service\Form\Generic;
use App\Service\Form\FormConstants;

class Textarea extends Generic
{
    protected string $pattern = '<textarea name="%s" type="%s" %s>%s</textarea>';

    public function __construct($name, $type, $label = '', array $wrappers = array(), array $attributes = array(), array $errors = array())
    {
        if(!empty($wrappers)){
            $wrappers['input']['type'] = FormConstants::TEXTAREA;
        }

        parent::__construct($name, $type, $label, $wrappers, $attributes, $errors);
    }

    protected function getTextarea()
    {
        $value = $this->getContent();

        return sprintf($this->pattern,
            $this->name,
            $this->type,
            $this->getAttributes(),
            $value
        ) . PHP_EOL;
    }

    public function getInput(): string
    {
        return $this->getTextarea();
    }

    private function getContent(): string
    {
        $value = "";
        if(isset($this->attributes['value']))
        {
            $value = $this->attributes['value'];
            unset($this->attributes['value']);
        }
        return $value;
    }
}