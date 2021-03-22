<?php
namespace App\Module\Observer\Generic;

use App\Module\Access;
use App\Module\Observer\Generic\GenericObserver;
use App\Service\Logger\Logger;
use App\Service\Logger\MessageSheme;
use App\Module\FormHandling\User\Login\Login;
use App\Module\FormHandling\User\Register\Register;
use App\Module\Observer\Observable;
use App\Service\Session\Session;
use App\Service\Session\SessionSecurity;

class SessionObserver extends GenericObserver
{
    private $sessionSecurity;
    private $logger;

    public function __construct(Observable $observable)
    {
        $this->logger = new Logger();

        $this->sessionSecurity = new SessionSecurity(new Session());

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
            if(!$this->sessionSecurity->manageSessionSecurity())
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
                $this->sessionSecurity->changeSessionUser($observable->getObject()->getNick());
        }
    }
}