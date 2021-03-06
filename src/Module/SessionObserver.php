<?php
namespace App\Module;

use App\Access\Access;
use App\Logger\Logger;
use App\Logger\MessageSheme;
use App\Module\Form\Login\Login;
use App\Module\Form\Register\Register;
use App\Module\Observer\Observable;
use App\Session\Session;
use App\Session\SessionManager;

class SessionObserver extends GenericObserver
{
    private $sessionManager;
    private $logger;

    public function __construct(Observable $observable)
    {
        $this->logger = new Logger();

        $this->sessionManager = new SessionManager(new Session());

        parent::__construct($observable);
    }

    public function update(Observable $observable)
    {
        if($observable === $this->observable)
            $this->doUpdate($observable);
    }

    protected function doUpdate(Observable $observable)
    {
        if($observable->getProcessStatus() === "session")
        {
            if(!$this->sessionManager->manage())
            {
                $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
                $this->logger->critical("The user requesting access to the session could not be verified", [$config]);

                header("Location: " . Access::$PATH_401);
                die();
            }
        }

        if($observable->getProcessStatus() === "correct")
        {
            // change session user
            if($observable instanceof Login || $observable instanceof Register)
                $this->sessionManager->changeSessionUser($observable->getObject()->getNick());
        }
    }
}