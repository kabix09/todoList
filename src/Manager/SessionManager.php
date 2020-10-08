<?php
namespace App\Manager;

use App\Entity\Mapper\SessionMapper;
use App\Entity\Session;

class SessionManager extends BaseManager
{
    public function __construct($data = NULL)
    {
        $this->setObject($data);

        parent::__construct(NULL);
    }

    protected function setObject($data)
    {
        if(is_array($data))
            $this->object = SessionMapper::arrayToEntity($data);
        elseif($data instanceof Session)
            $this->object = $data;
        else
        {
            $this->object = new Session();
            $this->init();
        }
    }

    public function return(): Session{
        return $this->object;
    }

    public function update(): bool
    {
        // TODO: Implement update() method.
    }

    // ---- base entity setting functions ----
    private function setUserIP(): void
    {
        $this->object->setUserIP($_SERVER['REMOTE_ADDR']);
    }
    private function setBrowserData(): void
    {
        $this->object->setBrowserData($_SERVER['HTTP_USER_AGENT']);
    }

    // ---- base entity config functions ----
    public function updateSessionKey(string $sessionKey): void
    {
        $this->object->setSessionKey($sessionKey);
    }
    public function updateUserNick(?string $userNick = NULL): void
    {
        $this->object->setUserNick($userNick);
    }
    public function updateCreateTime(?string $newTime = NULL): void
    {
        if(is_null($newTime))
            $newTime = $this->getDate();

        $this->object->setCreateTime($newTime);
    }

    // ---- ---- support functions
    public function init(){
        $this->setUserIP();
        $this->setBrowserData();

        //$this->updateCreateTime();
    }
}