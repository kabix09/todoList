<?php
namespace App\Service\Form;

class Generic
{
    const DEFAULT_TYPE = FormConstants::TYPE_TEXT;
    const DEFAULT_WRAPPER = 'div';

    protected string $name = "";
    protected string $type = self::DEFAULT_TYPE;
    protected string $label = "";
    protected array $errors = array();
    protected array $wrappers = array();
    protected array $attributes = array();
    protected string $pattern = '<input type="%s" name="%s" %s>';

    public function __construct(string $name, $type, string $label = '',
                                array $wrappers = array(),
                                array $attributes = array(),
                                array $errors = array())
    {
        $this->name = $name;
        $this->type = $type ?? self::DEFAULT_TYPE;
        $this->label = $label;
        $this->errors = $errors;
        $this->attributes = $attributes;

        if($wrappers){
            $this->wrappers = $wrappers;
        }else{
            $this->wrappers[FormConstants::INPUT]['type'] = self::DEFAULT_WRAPPER;
            $this->wrappers[FormConstants::LABEL]['type'] = self::DEFAULT_WRAPPER;
            $this->wrappers[FormConstants::ERRORS]['type'] = self::DEFAULT_WRAPPER;
        }
        $this->attributes['id'] = $name;
    }

    protected function getWrapperPattern(string $type) : string {
        $pattern = '<' . $this->wrappers[$type]['type'];

        foreach ($this->wrappers[$type] as $key => $value){
            if($key === 'type')
                continue;

            if($key === 'for')
                $value = $this->attributes['id'] ?? NULL;

            if($value)
                $pattern .= ' ' . $key . ' = "' . $value . '"';
            else
                $pattern .= ' ' . $key . ' ';
        }

        $pattern .= '>%s</' . $this->wrappers[$type]['type'] . '>';
        return $pattern;
    }

    protected function getAttributes() : string {
        $attributes = '';

        foreach ($this->attributes as $key => $value){
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
        return sprintf($this->getWrapperPattern(FormConstants::LABEL), $this->label) . PHP_EOL;
    }

    public function getInput() : string {
        return sprintf($this->pattern,
            $this->type,
            $this->name,
            $this->getAttributes()
        ) . PHP_EOL;
    }

    public function getInputWithLabel(bool $newLine = false) : string {
        return $this->getLabel() . $this->getInput() . ($newLine ? '</br>' : '');
    }

    public function getErrors() : string{
        if(!$this->errors || count($this->errors) == 0)
            return '';

        $errorPattern = '<li class="error">%s</li>';

        $html = '<ul>';
        foreach ($this->errors as $error)
            $html .= sprintf($errorPattern, $error);
        $html .= '</ul>';

        return sprintf($this->getWrapperPattern(FormConstants::ERRORS), trim($html));
    }

    public function render(bool $wrapped = FALSE, bool $brElement = FALSE) : string {
        if($wrapped)
            return sprintf($this->getWrapperPattern(FormConstants::WRAPPER), $this->getInputWithLabel($brElement) . $this->getErrors());

        return $this->getInputWithLabel($brElement) . $this->getErrors();
    }


    // getters & setters declarations

    /**
     * @param string $attribute
     */
    public function setAttribute(string $key, $attribute): void
    {
        if(isset($this->attributes[$key]))
            $this->attributes[$key] = $attribute;
        else
            $this->attributes[$key] = $attribute;

    }
}