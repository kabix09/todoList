<?php
namespace App\Service\Form\Factory;

use App\Service\Form\Form;
use App\Service\Form\FormConstants;
use App\Service\Form\Generic;

final class Factory
{
    private array $elements = array();

    public function generate(array $config, ?string $token = NULL){
        foreach ($config as $key => $element){
            $element['errors'] = $element['errors'] ?? array();
            $element['wrappers'] = $element['wrappers'] ?? array();
            $element['attributes'] = $element['attributes'] ?? array();

            $this->elements[$key] = new $element['class']
            (
                $key,   // name
                $element['type'],
                $element['label'],
                $element['wrappers'],
                $element['attributes'],
                $element['errors']
            );
        }

        if(isset($this->elements['hidden']) && $token)
            $this->elements['hidden']->setAttribute("value", $token);
    }

    public function render(array $formConfig, bool $wrapp = FALSE, bool $brElement = FALSE){
        $content = '';

        foreach ($this->elements as $name => $element){
            $content .= $element->render($wrapp, $brElement);
        }

        $form = new Form(
            $formConfig['name'],
            FormConstants::FORM,
            '',
            array(),
            $formConfig['attributes']
        );

        return $form->getForm($content);
    }

    public function getElements() : array {
        return $this->elements;
    }

    public function getElement(string $name) : Generic {
        return $this->elements[$name];
    }
}