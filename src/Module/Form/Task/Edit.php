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
                throw new \RuntimeException("couldn't update task id: {$this->object->getId()} :/");
            }else
            {
                $this->logger->info("Successfully edited task with id: {$this->object->getId()}", [
                    "personalLog" => TRUE,
                    "userFingerprint" => $this->object->getOwner(),
                    "className" => __CLASS__,
                    "functionName" => __FUNCTION__
                ]);
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }else
        {
            $this->logger->error("An attempt to edit task with id: {$this->data['id']} has failed", [
                "personalLog" => TRUE,
                "userFingerprint" => $this->data['owner'],
                "className" => __CLASS__,
                "functionName" => __FUNCTION__
            ]);
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