<?php
namespace App\Access\TaskScript;

use App\Access\BaseTaskAccess;
use App\Entity\Task;
use App\Logger\MessageSheme;
use App\Manager\TaskManager;

final class ChangeStatus extends BaseTaskAccess
{
    protected const NEW_STATUS = 3;
    public const QUERY_PARAMETERS = [self::ID => "id", self::OWNER => "owner", self::NEW_STATUS => "new"];

    protected function main(array $queryParams): void
    {
        $taskManager = new TaskManager($this->getTaskCollection($queryParams[self::QUERY_PARAMETERS[self::ID]]), $this->taskRepository);
        if($taskManager->changeStatus($queryParams[self::QUERY_PARAMETERS[self::NEW_STATUS]]))
        {
            // log event
            $config = new MessageSheme($this->session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully changed status task with id: {$queryParams[self::QUERY_PARAMETERS[self::ID]]} to: {$queryParams[self::QUERY_PARAMETERS[self::NEW_STATUS]]}", [$config]);
            // no need to remove from session because
            // index.php automatically refresh task list
            $this->redirectToHome();
        }else{
            throw new \RuntimeException("An attempt to finish task with id: {$queryParams[self::QUERY_PARAMETERS[self::ID]]} has failed");
        }
    }

    private function getTaskCollection(int $id): ?Task{
        foreach ($this->session['user']->getTaskCollection() as $task)
        {
            if($task->getId() == $id)
                return $task;
        }
        return NULL;
    }
}