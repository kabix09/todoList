<?php
namespace App\Module\FormHandling\Task;

use ConnectionFactory\Connection;
use App\Entity\User;
use App\Entity\Task;
use App\Service\Logger\MessageSheme;
use App\Service\Manager\TaskManager;
use App\Module\FormHandling\Task\TaskForm;

final class Create extends TaskForm
{
    protected const TITLE_ERROR = "this task already exists";

    private User $user;
    public function __construct(array $formData, Connection $connection, User $user)
    {
        parent::__construct($formData, $connection);
        $this->user = $user;
    }

    protected function doHandler()
    {
        $this->fixDate();

        if (!$this->checkTitle())
        {
            $this->errors['title'][] = self::TITLE_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if ($this->processStatus === NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            // prepare task
            $this->object = $this->prepareTask();

            // insert new task
            if(!$this->repository->insert($this->object))
            {
                throw new \RuntimeException("couldn't create new task :/");
            }else
            {
                $config = new MessageSheme($this->object->getOwner(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->info("Successfully created new task with title: {$this->object->getTitle()}", [$config]);
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }else
        {
            $config = new MessageSheme($this->user->getNick(), __CLASS__, __FUNCTION__, TRUE);

            $this->logger->error("An attempt to create new task has failed", [$config]);
        }
    }

    public function checkTitle(){
        $tasks = $this->repository->fetchByOwner($this->user->getNick());

        if(!is_null($tasks))
        {
            foreach ($tasks as $task)
            {
                if($task->getTitle() === $this->data['title'])
                    $this->object = $task;
            }

            if($this->object){
                return FALSE;
            }
        }
        return TRUE;
    }


    protected function prepareTask(): Task
    {
        // set other data
        $taskManager = new TaskManager($this->data, $this->repository);

        $taskManager->setAuthor($this->user->getNick(), TRUE);
        $taskManager->setStatus();

        return $taskManager->return();
    }
}