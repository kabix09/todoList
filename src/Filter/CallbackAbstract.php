<?php
namespace App\Filter;

use App\Filter\Elements\Result;

abstract class CallbackAbstract implements CallbackInterface
{
    protected $message;
    protected $filteredValue;

    protected function createResult(?bool $value = NULL) : Result
    {
        return new Result($value ?? $this->filteredValue, $this->message);
    }
}