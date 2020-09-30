<?php
namespace App\Session;

final class SessionManager
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function manage(): bool
    {
        if($this->verifyUser())
        {
            $this->checkRequestsAmount();

            $this->checkSessionTime();

            return TRUE;
        }else
            return FALSE; // redirect to logout script and force user to login again
    }

    public function changeSessionUser(string $nick): bool{
        return $this->session->updateSessionUser($nick);
    }
    //-----------------------------------------------------------------------
    public function verifyUser(): bool
    {
        if($this->session->verify->checkUserAgent() && $this->session->verify->checkUserIP())
            return TRUE;

        return FALSE;
    }

    public function checkRequestsAmount(): void{
        if(!$this->session->counter->checkCounter())
        {
                // if value out of range
            $this->revaluateSessionParameters();
        }else {
            $this->increase();  // request amount ++
            $this->session['counter'] = $this->session->counter::getItem();     // catch new value in session
        }
    }

    public function checkSessionTime(): void{
        if($this->session->verify->isLoginSessionExpired())
        {
                // if session expired
            $this->revaluateSessionParameters();
        }
    }

    //####################################################################
    private function revaluateSessionParameters(){
            // 1 regenerate session id
        $this->session->regenerateID();

            // 2 update session time
        $this->session->refreshSessionTime();

            // 3 refresh session entity parameter
        $this->session->refreshSessionEntity();

            // 4 refresh requests counter
        $this->session->counter::resetItem();
                // save new value in session
            $this->session['counter'] = $this->session->counter::getItem();

    }

        // before function, call verifyUser function
    private function increase(): void{
        $this->session->counter::increaseItem();
    }
}
