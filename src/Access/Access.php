<?php
namespace App\Access;

use App\Entity\User;
use App\Session\SessionManager;
use App\Session\Session;

class Access
{
    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    protected function isLogged() : bool
    {
        return
            isset($this->session['user']) && $this->session['user'] instanceof User;
    }

    protected function redirectToHome(): void
    {
        header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
        exit();
    }

    protected function sessionManage(): void
    {
        $sessionManager = new SessionManager($this->session);
        if(!$sessionManager->manage())
        {
            // logout and redirect to login page
            die("session error - try to refresh page :/"); // TODO - fix error and behaviour
        }
    }
}
