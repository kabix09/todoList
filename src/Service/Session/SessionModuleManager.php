<?php declare(strict_types=1);

namespace App\Service\Session;

use App\Service\EntityManager\Session\SessionManager;
use App\Entity\Session AS SessionEntity;

class SessionModuleManager extends SessionManager
{
    public function fetchSession(): ?SessionEntity
    {
        $object = null;

        if(isset($_COOKIE['PHPSESSID'])) {
            $object = $this->repository->fetchBySessionKey($_COOKIE['PHPSESSID']);
        }
        else {          // return first object from generator's array, technically there MUST be exactly ONE object with this criteria
            $object = $this->repository->find(array(), [
                "WHERE" => NULL,
                "AND" => ["user_ip = '{$_SERVER["REMOTE_ADDR"]}'", "browser_data = '{$_SERVER["HTTP_USER_AGENT"]}'"]
            ])->current();
        }

        return $object;
    }

    public function updateSessionUser(string $nick): bool
    {
        $tmpObject = $this->objectBuilder->getInstance();

        if($tmpObject->getUserNick() !== NULL)
            return TRUE;

        $this->updateUserNick($nick);
        $this->updateCreateTime();

        return $this->refreshSessionEntity();
    }

    public function refreshSessionEntity(): bool
    {
        $tmpObject = $this->objectBuilder->getInstance();

        return $this->update([
            "WHERE" => NULL,
            "AND" => ["user_ip = '{$tmpObject->getUserIP()}'", "browser_data = '{$tmpObject->getBrowserData()}'"]
        ]);
    }

    public function refreshSessionTime(): void
    {
        $this->updateCreateTime();
    }
}