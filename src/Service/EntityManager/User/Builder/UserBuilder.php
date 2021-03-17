<?php declare(strict_types=1);
namespace App\Service\EntityManager\User\Builder;

use App\Entity\User;
use App\Service\EntityManager\IEntityBuilder;

final class UserBuilder implements \App\Service\EntityManager\IEntityBuilder
{
    private User $userInstance;

    public function __construct(User $userInstance)
    {
        $this->userInstance = $userInstance;
    }

    public function getInstance(): User
    {
        return clone $this->userInstance;
    }

    public function changePassword(string $newPassword): void
    {
        $this->userInstance->setPassword($newPassword);
    }

    public function setKey(string $newKey): void
    {
        $this->userInstance->setKey($newKey);
    }

    public function setCreateAccountDate(string $newDate): void
    {
        $this->userInstance->setCreateAccountDate($newDate);
    }

    public function setLastLoginDate(string $newDate): void
    {
        $this->userInstance->setLastLoginDate($newDate);
    }

    public function setAccountStatus(string $newStatus): void
    {
        $this->userInstance->setStatus($newStatus);
    }

    public function setTaskList(array $taskList): void
    {
        $this->userInstance->setTaskCollection($taskList);
    }



}
