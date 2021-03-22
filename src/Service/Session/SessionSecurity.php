<?php declare(strict_types=1);

namespace App\Service\Session;

use App\Service\Session\Counter\Counter;

final class SessionSecurity
{
    private Session $sessionModule;
    private SessionVerify $sessionVerify;
    private SessionModuleManager $sessionManager;

    public function __construct(Session $sessionModule)
    {
        $this->sessionModule = $sessionModule;
        $this->sessionModule->getSessionVerify($this);
        $this->sessionModule->getSessionManager($this);
    }

    public function setSessionVerify(SessionVerify $sessionVerify): void
    {
        $this->sessionVerify = $sessionVerify;
    }
    public function setSessionManager(SessionModuleManager $sessionManager): void
    {
        $this->sessionManager = $sessionManager;
    }

    public function manageSessionSecurity(): bool
    {
        if($this->verifyUser())
        {
            $this->checkRequestsAmount();

            $this->checkSessionTime();

            return TRUE;
        }

        return FALSE;       // redirect to logout script and force user to login again
    }

    public function changeSessionUser(string $nick): bool
    {
        return $this->sessionManager->updateSessionUser($nick);
    }

    private function verifyUser(): bool
    {
        if($this->sessionVerify->checkUserAgent() && $this->sessionVerify->checkUserIP())
            return TRUE;

        return FALSE;
    }

    private function checkRequestsAmount(): void{
        if(!Counter::checkCounter()) {        // if value out of range

            $this->revaluateSessionParameters();
        }
        else {
            Counter::increaseItem();  // request amount ++
            $this->sessionModule['counter'] = Counter::getItem();     // catch new value in session
        }
    }

    private function checkSessionTime(): void{
        if($this->sessionVerify->isLoginSessionExpired())  {        // if session expired

            $this->revaluateSessionParameters();
        }
    }

    //####################################################################
    private function revaluateSessionParameters(): void
    {
        $this->sessionModule->regenerateID();         // 1 step - regenerate session id

        $this->sessionManager->refreshSessionTime();   // 2 step - update session time

        $this->sessionManager->refreshSessionEntity(); // 3 step - refresh session entity parameter

        Counter::resetItem();   // 4 step - refresh requests counter

            $this->sessionModule['counter'] = Counter::getItem(); // save new value in session

    }
}
