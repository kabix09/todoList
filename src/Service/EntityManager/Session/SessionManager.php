<?php
namespace App\Service\EntityManager\Session;

use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Service\EntityManager\BaseManager;
use App\Service\EntityManager\Session\Builder\SessionBuilder;

class SessionManager extends BaseManager
{
    public function __construct(SessionBuilder $sessionBuilder, ?SessionRepository $sessionRepository = NULL)
    {
        parent::__construct($sessionBuilder, $sessionRepository);
    }

    public function return(): Session{
        return $this->objectBuilder->getInstance();
    }

    public function prepareInstance(array $config = []): void
    {
        parent::prepareInstance(
            [
                Session::MAPPING['user_ip'] => $_SERVER['REMOTE_ADDR'],
                Session::MAPPING['browser_data'] => $_SERVER['HTTP_USER_AGENT']
            ]
        );
    }

    public function updateInstance (Session $copiedObject){
        $this->objectBuilder->setInstance($copiedObject);
    }


    // ---- base entity config functions ----
    public function updateSessionKey(string $sessionKey): void
    {
        $this->objectBuilder->setSessionKey($sessionKey);
    }
    public function updateUserNick(?string $userNick = NULL): void
    {
        $this->objectBuilder->setUserNick($userNick);
    }
    public function updateCreateTime(string $newTime = ""): void
    {
        if(empty($newTime))
            $newTime = $this->getDate();

        $this->objectBuilder->setCreateTime($newTime);
    }
}