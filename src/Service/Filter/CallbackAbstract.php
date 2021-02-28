<?php
namespace App\Service\Filter;

use App\Service\Filter\Elements\Result;

abstract class CallbackAbstract implements CallbackInterface
{
    protected $message;
    protected $filteredValue;

    protected function createResult(?bool $flag = NULL) : Result
    {
        return new Result($this->filteredValue, $this->message ?? array(), $flag);
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