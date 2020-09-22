<?php
namespace App\Module;

use App\Module\Observer\Observer;
use App\Module\Observer\Observable;
use App\Session\Session;

final class ErrorObserver implements Observer
{
    private $observable;
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

    public function doUpdate(Observable $observable)
    {
        if($observable->getProcessStatus() === "errors")
        {
            $session = new Session();
            $session[$this->nameBuilder('Errors')] = $observable->getErrors();
        }
    }

    private function nameBuilder(string $suffix = "") : string{
        $array = explode("\\", get_class($this->observable));

        return strtolower(array_values(array_slice($array, -1))[0]) . ucfirst($suffix);
    }

}