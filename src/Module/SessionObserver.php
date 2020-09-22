<?php

namespace App\Module;

use App\Module\Observer\Observable;
use App\Module\Observer\Observer;
use App\Session\Session;
use App\Session\SessionManager;

class SessionObserver implements Observer
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

    private function doUpdate(Observable $observable)
    {
        if($observable->getProcessStatus() === "session")
        {
            $sessionManager = new SessionManager(new Session());
            if(!$sessionManager->manage())
            {
                    // logout and redirect to login form -> toDo ???
                header("Location: ./logout.php");
                header("Location: ./login.php");

                exit("user verify fail - please ty login again");   // don't show message -> toDo
            }

        }
    }
}