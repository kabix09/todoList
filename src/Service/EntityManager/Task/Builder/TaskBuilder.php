<?php declare(strict_types=1);
namespace App\Service\EntityManager\Task\Builder;

use App\Entity\Task;
use App\Service\EntityManager\IEntityBuilder;

final class TaskBuilder implements IEntityBuilder
{
    private Task $taskInstance;

    public function __construct(Task $taskInstance)
    {
        $this->taskInstance = $taskInstance;
    }

    public function getInstance(): Task
    {
        return clone $this->taskInstance;
    }

    public function setAuthor(string $newAuthor)
    {
        $this->taskInstance->setAuthor($newAuthor);
    }

    public function setOwner(string $newOwner)
    {
        $this->taskInstance->setOwner($newOwner);
    }

    public function setCreateDate(string $newDate)
    {
        $this->taskInstance->setCreateDate($newDate);
    }

    public function setStatus(string $newStatus)
    {
        if($this->validStatus($newStatus)) {
            $this->taskInstance->setStatus($newStatus);
        }
    }

    private function validStatus(string $newStatus) : bool
    {
        if(!in_array($newStatus, Task::STATUS))
            throw new \RuntimeException("illegal status");

        return TRUE;
    }
}