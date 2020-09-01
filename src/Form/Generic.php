<?php
namespace App\Form;

class Generic
{
    const DEFAULT_TYPE = FormConstants::TYPE_TEXT;
    const DEFAULT_WRAPPER = 'div';

    protected $name = "";
    protected $type = self::DEFAULT_TYPE;
    protected $label = "";
    protected $errors = array();
    protected $wrappers = "";
    protected $attribures = array();
    protected $pattern = '<input type="%s" name="%s" %s>';

    public function __construct(string $name, $type, string $label,
                                array $wrappers = array(),
                                array $attributes = array(),
                                array $errors = array())
    {
        $this->name = $name;
        $this->type = $type ?? self::DEFAULT_TYPE;
        $this->label = $label;
        $this->errors = $errors;
        $this->attribures = $attributes;
        if($wrappers){
            $this->wrappers = $wrappers;
        }else{
            $this->wrappers[FormConstants::INPUT]['type'] = self::DEFAULT_WRAPPER;
            $this->wrappers[FormConstants::LABEL]['type'] = self::DEFAULT_WRAPPER;
            $this->wrappers[FormConstants::ERRORS]['type'] = self::DEFAULT_WRAPPER;
        }
        $this->attribures['id'] = $name;
    }

    protected function getWrapperPattern(string $type) : string {
        $pattern = '<' . $this->wrappers[$type]['type'];

        foreach ($this->wrappers[$type] as $key => $value){
            if($key === 'type')
                continue;

            if($key === 'for')
                $value = $this->attribures['id'] ?? NULL;

            if($value)
                $pattern .= ' ' . $key . ' = "' . $value . '"';
            else
                $pattern .= ' ' . $key . ' ';
        }

        $pattern .= '>%s</' . $this->wrappers[$type]['type'] . '>';
        return $pattern;
    }

    private function getAttributes() : string {
        $attributes = '';

        foreach ($this->attribures as $key => $value){
            $key = strtolower($key);
            if($value)
            {
                if($key == 'href')
                    $value = urlencode($value);

                $attributes .= $key . ' = "' . $value . '" ';
            }else
                $attributes .= $key . ' ';
        }
        return trim($attributes);
    }


    public function getLabel() : string {
        return sprintf($this->getWrapperPattern(FormConstants::LABEL), $this->label);
    }

    public function getInput() : string {
        return sprintf($this->pattern,
            $this->type,
            $this->name,
            $this->getAttributes()
        );
    }

    public function getInputWithLabel() : string {
        return $this->getLabel() . $this->getInput();
    }

    public function getErrors() : string{
        if(!$this->errors || count($this->errors) == 0)
            return '';

        $errorPattern = '<li>%s</li>';

        $html = '<ul>';
        foreach ($this->errors as $error)
            $html .= sprintf($errorPattern, $error);
        $html .= '</ul>';

        return sprintf($this->getWrapperPattern(FormConstants::ERRORS), trim($html));
    }

    public function render(bool $wrapped = FALSE) : string {
        if($wrapped)
            return sprintf($this->getWrapperPattern(FormConstants::WRAPPER), $this->getInputWithLabel() . $this->getErrors());

        return $this->getInputWithLabel() . $this->getErrors();
    }
}