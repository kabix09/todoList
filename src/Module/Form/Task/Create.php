<?php
namespace App\Module\Form\Task;

use App\Connection\Connection;
use App\Entity\User;
use App\Entity\Task;
use App\Manager\TaskManager;
use App\Module\Form\TaskForm;

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
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
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