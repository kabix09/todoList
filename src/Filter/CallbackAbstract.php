<?php
namespace App\Filter;

use App\Filter\Elements\Result;

abstract class CallbackAbstract implements CallbackInterface
{
    protected $message;
    protected $filteredValue;

    protected function createResult(?bool $value = NULL) : Result
    {
        return new Result($value ?? $this->filteredValue, $this->message ?? array());
    }

    protected function resetOldMessage()
    {
        // in other case $message is treated like STATIC variable and overwrite in the next call object as function (__invoke() method)
        // in other words, if message gets value from previous call in other anonymous class,
        // this value is remembered in next call in new anonymous class
        // toDo BYT WHY???
        $this->message  = array();
    }
}