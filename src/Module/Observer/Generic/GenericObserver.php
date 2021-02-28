<?php
namespace App\Module\Observer\Generic;

use App\Module\Observer\Observable;
use App\Module\Observer\Observer;

abstract class GenericObserver implements Observer
{
    protected $observable;

    public function __construct(Observable $observable)
    {
        $this->observable = $observable;
        $observable->attach($this);
    }

    public function update(Observable $observable)
    {
        if($observable === $this->observable)
            $this->doUpdate($observable);
    }

    abstract protected function doUpdate(Observable $observable);
}