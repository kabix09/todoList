<?php
namespace App\Module\Observer;

final class ErrorObserver implements Observer
{
    private $observabe;
    public function __construct(Observable $observabe)
    {
        $this->observabe = $observabe;
        $observabe->attach($this);
    }

    public function update(Observable $observable)
    {
        if($observable === $this->observabe)
            $this->doUpdate($observable);
    }

    public function doUpdate(Observable $observabe)
    {
        if($observabe->getProcessStatus() === "errors")
        {
            $_SESSION['formErrors'] = $observabe->getErrors();
        }
    }
}