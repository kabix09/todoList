<?php

namespace App\Service\Form;

class Form extends Generic
{
    protected string $pattern = '<form name="%s" %s>%s</form>';

    public function getForm(string $content = ''): string
    {
        return sprintf($this->pattern,
            $this->name,
            $this->getAttributes(),
            $content
        );
    }
}