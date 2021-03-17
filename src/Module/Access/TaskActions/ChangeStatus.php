<?php
namespace App\Module\Access\TaskActions;

use App\Module\Access\BaseTaskAccess;
use App\Entity\Task;
use App\Service\EntityManager\Task\Builder\TaskBuilder;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\Task\TaskManager;

final class ChangeStatus extends BaseTaskAccess
{
    protected const NEW_STATUS = 3;
    public const QUERY_PARAMETERS = [self::ID => "id", self::OWNER => "owner", self::NEW_STATUS => "new"];

    protected function main(array $queryParams): void
    {
        $handledTask = $this->handleTask(
            $queryParams[self::QUERY_PARAMETERS[self::ID]],
            $queryParams[self::QUERY_PARAMETERS[self::OWNER]]
        );

        $taskManager = new TaskManager(new TaskBuilder($handledTask), $this->taskRepository);

        $taskManager->prepareInstance(
            [
                Task::MAPPING['status'] => $queryParams[self::QUERY_PARAMETERS[self::NEW_STATUS]]
            ]
        );

        if($taskManager->update())
        {
            // log event
            $config = new MessageSheme($this->session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully changed status task with id: {$queryParams[self::QUERY_PARAMETERS[self::ID]]} to: {$queryParams[self::QUERY_PARAMETERS[self::NEW_STATUS]]}", [$config]);

            // update user handled in session
            $this->session['user'] = $taskManager->return();

            // no need to remove from session because
            // index.php automatically refresh task list
            $this->redirectToHome();
        }else{
            throw new \RuntimeException("An attempt to finish task with id: {$queryParams[self::QUERY_PARAMETERS[self::ID]]} has failed");
        }
    }

    private function handleTask(int $id, string $owner): Task
    {
        /*
         * Tw ways:
         *  - fetch task from db
         *  - fetch task from users session memory
         * Which one is better??
         */
        try {
            $handledTask = $this->taskRepository->fetchById($id);

            if(!is_null($handledTask)) {
                throw new \RuntimeException("Task with id: {$id} doesn't exist!");
            }

            if($handledTask->getOwner() !== $owner) {
                throw new \RuntimeException("User: {$owner} doesn't have task with id: {$id}!");
            }

            return $handledTask;

        } catch (\Exception $e) {
            $config = new MessageSheme($owner, __CLASS__, __FUNCTION__, TRUE);
            $this->logger->error($e->getMessage(), [$config]);
        }
    }
}
