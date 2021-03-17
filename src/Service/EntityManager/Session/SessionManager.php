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

    public function update(array $criteria=[]): bool
    {
        // TODO: Implement update() method.
    }

    // ---- base entity setting functions ----
    private function setUserIP(): void
    {
        $this->objectBuilder->setUserIP($_SERVER['REMOTE_ADDR']);
    }
    private function setBrowserData(): void
    {
        $this->objectBuilder->setBrowserData($_SERVER['HTTP_USER_AGENT']);
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
    public function updateCreateTime(?string $newTime = NULL): void
    {
        if(is_null($newTime))
            $newTime = $this->getDate();

        $this->objectBuilder->setCreateTime($newTime);
    }

    // ---- ---- support functions
    public function init(){
        $this->setUserIP();
        $this->setBrowserData();

        //$this->updateCreateTime();
    }

    // ---- function to clone object values                     TODO - is required????
    public function updateInstance (Session $copiedObject){
        $this->objectBuilder->setInstance($copiedObject);
    }
}