<?php

namespace App\Module;

use App\Module\Form\Login\Login;
use App\Module\Form\Register\Register;
use App\Module\Observer\Observable;
use App\Module\Observer\Observer;
use App\Session\Session;
use App\Session\SessionManager;

class SessionObserver implements Observer
{
    private $sessionManager;
    private $observable;
    public function __construct(Observable $observable)
    {
        $this->sessionManager = new SessionManager(new Session());

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
            if(!$this->sessionManager->manage())
            {
                    // logout and redirect to login form -> toDo ???
                header("Location: ./logout.php");
                header("Location: ./login.php");

                exit("user verify fail - please ty login again");   // don't show message -> toDo
            }

            // but when, not all time
            //$sessionManager->changeSessionUser();
        }

        if($observable->getProcessStatus() === "correct")
        {
            // change session user
            if($observable instanceof Login || $observable instanceof Register)
                $this->sessionManager->changeSessionUser($observable->getObject()->getNick());
        }
    }
}