<?php declare(strict_types=1);
namespace App\Service\EntityManager\Session\Builder;

use App\Entity\Session;
use App\Service\EntityManager\IEntityBuilder;

class SessionBuilder implements IEntityBuilder
{
    private Session $sessionInstance;

    public function __construct(Session $sessionInstance)
    {
        $this->sessionInstance = $sessionInstance;
    }

    public function setInstance(Session $newInstance): void
    {
        foreach (get_class_methods(Session::class) as $functionName)
        {
            if(strpos($functionName, "set") === 0)
            {
                $getter = "get" . ucfirst(substr($functionName, 3));
                $this->sessionInstance->$functionName($newInstance->$getter());
            }
        }
    }
    public function getInstance()
    {
        return clone $this->sessionInstance;
    }

    public function setUserIP(string $newIP): void
    {
        $this->sessionInstance->setUserIP($newIP);
    }

    public function setBrowserData(string $newBrowserData): void
    {
        $this->sessionInstance->setBrowserData($newBrowserData);
    }

    public function setSessionKey(string $newKey): void
    {
        $this->sessionInstance->setSessionKey($newKey);
    }

    public function setUserNick(?string $newNick): void
    {
        $this->sessionInstance->setUserNick($newNick);
    }

    public function setCreateTime(string $newCreationTime): void
    {
        $this->sessionInstance->setCreateTime($newCreationTime);
    }
}