<?php

namespace App\Module\FormHandling\Task;

use App\Entity\Mapper\TaskMapper;
use App\Service\EntityManager\Task\Builder\TaskBuilder;
use ConnectionFactory\Connection;
use App\Entity\Task;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\Task\TaskManager;
use App\Module\FormHandling\Task\TaskForm;

final class Edit extends TaskForm
{
    private int $taskID;

    public function __construct(array $formData, Connection $connection, int $id)
    {
        parent::__construct($formData, $connection);

        $this->taskID = $id;
    }

    protected function doHandler()
    {
        $this->fixDate();

        if($this->processStatus === NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            $this->prepareTask($this->object);

            // update existing task
            if(!$this->repository->update($this->object, [
                "WHERE" => NULL,
                "AND" => ["title='{$this->object->getTitle()}'","owner='{$this->object->getOwner()}'"]
            ]))
            {
                throw new \RuntimeException("couldn't update task id: {$this->object->getId()} :/");
            }else
            {
                $config = new MessageSheme($this->object->getOwner(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->info("Successfully edited task with id: {$this->object->getId()}", [$config]);
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }else
        {
            $config = new MessageSheme($this->data['owner'], __CLASS__, __FUNCTION__, TRUE);
            $this->logger->error("An attempt to edit task with id: {$this->taskID} has failed", [$config]);
        }
    }

    protected function prepareTask(Task $overwrittenObject): bool
    {
        $taskManager = new TaskManager(new TaskBuilder(TaskMapper::convertArrayToEntity($this->data)), $this->repository);
        $taskManager->manageStatus();

        return TaskMapper::overwriteEntity($taskManager->return(), $overwrittenObject);
    }
}