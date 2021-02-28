<?php
namespace App\Module\Observer\Generic;

use App\Module\Observer\Generic\GenericObserver;
use App\Module\Observer\Observable;
use App\Service\Session\Session;

final class ErrorObserver extends GenericObserver
{
    public function __construct(Observable $observable)
    {
        parent::__construct($observable);
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