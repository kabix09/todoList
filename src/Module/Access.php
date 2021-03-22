<?php
namespace App\Module;

use App\Entity\User;
use App\Service\Logger\Logger;
use App\Service\Logger\MessageSheme;
use App\Service\Session\SessionSecurity;
use App\Service\Session\Session;

class Access
{
    static string $PATH_401;
    protected Session $session;
    protected Logger $logger;

    public function __construct(Session $session)
    {
        $this->session = $session;

        $this->logger = new Logger();

        if (!isset(self::$PATH_401) || empty(self::$PATH_401))
            self::$PATH_401 = $_SERVER['REQUEST_SCHEME']. "://" . $_SERVER['HTTP_HOST'] . "/templates/error/401.php";
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
        $sessionSecurity = new SessionSecurity($this->session);
        if(!$sessionSecurity->manageSessionSecurity())
        {
            $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
            $this->logger->critical("The user requesting access to the session could not be verified", [$config]);

            header("Location: " . self::$PATH_401);
            die();
        }
    }
}
