<?php

namespace App\Module\Form\Task;

use App\Connection\Connection;
use App\Entity\Task;
use App\Manager\TaskManager;
use App\Module\Form\TaskForm;

final class Edit extends TaskForm
{

    protected function doHandler()
    {
        if($this->processStatus === NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            $this->object = $this->prepareTask();

            // update existing task
            if(!$this->repository->update($this->object, [
                "WHERE" => NULL,
                "AND" => ["id='{$this->object->getId()}'","owner='{$this->object->getOwner()}'"]
            ]))
            {
                throw new \RuntimeException("system error - couldn't update task id: {$this->task->getId()} :/");
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }
    }

    protected function prepareTask(): Task
    {
        // set other data
        $taskManager = new TaskManager($this->data, $this->repository);
        $taskManager->setStatus();

        return $taskManager->return();
    }
}