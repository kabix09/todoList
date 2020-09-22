<?php
namespace App\Module\Task;

use App\Connection\Connection;
use App\Entity\Task;
use App\Entity\User;
use App\Filter\Filter;
use App\Manager\TaskManager;
use App\Module\Observer\Observable;
use App\Module\Observer\Observer;
use App\Repository\TaskRepository;
use App\Token\Token;

class Create implements Observable
{
    const INCORECT_TITLE = "this task already exists";
    const PROCESS_STATUS = ["errors", "correct" , "session"];

    private array $observers = [];

    private array $data;
    private $task;
    private $user;
    private array $errors = [];

    private $processStatus = NULL;
    /**
     * @var TaskRepository
     */
    private TaskRepository $repository;

    public function __construct(array $formData, array $dbConfig, User $user)
    {
        $this->data = $formData;
        $this->user = $user;
        $this->repository = new TaskRepository(new Connection($dbConfig));
    }

    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer)
    {
        $this->observers = array_filter(
            $this->observers,
            function($object) use ($observer){
                return (!($object === $observer));
            }
        );
    }

    public function notify()
    {
        foreach ($this->observers as $observer)
        {
            $observer->update($this);
        }
    }

    // ######################################################################
    public function taskHandler(?string $serverToken = NULL, array $filter, array $assignments, string $author): bool{
        try{

            if ($this->checkToken($serverToken)) {

                if (!$this->validData($filter, $assignments))
                {
                    $this->processStatus = self::PROCESS_STATUS[0];
                } elseif (!$this->checkTitle())
                {
                    $this->errors['title'][] = self::INCORECT_TITLE;
                    $this->processStatus = self::PROCESS_STATUS[0];
                }

                if ($this->processStatus === NULL)
                {
                    $this->processStatus = self::PROCESS_STATUS[2];

                    $this->notify();

                        // set other data
                    $taskManager = new TaskManager($this->data, $this->repository);

                    $taskManager->setAuthor($author, TRUE);
                    $taskManager->setStatus();

                    $this->task = $taskManager->return();

                        // insert new task
                    if(!$this->repository->insert($this->task))
                    {
                        throw new \RuntimeException("system error - couldn't create new task :/");
                    }

                        // change status
                    $this->processStatus = self::PROCESS_STATUS[1];
                }

                $this->notify();

                if (empty($this->errors))
                    return TRUE;
            }

        }catch(\Exception $e)
        {
            var_dump($e->getMessage());
            die();
        }

        return FALSE;
    }

    // ----------------------------------------------------------------------
    public function checkToken(?string $serverToken = NULL): bool{
        if(!isset($serverToken))
            throw new \RuntimeException("token doesn't exists on server side ://");

        if(sodium_compare(
                (new Token($serverToken))->hash()->getToken(),
                (new Token($this->data['hidden']))->decode()->getToken()
            ) !== 0
        ) throw new \RuntimeException('detected cross-site attack on login form');

        unset($this->data['hidden']);

        return TRUE;
    }

    public function validData(array $filter, array $assignments): bool{
        $filter = new Filter($filter, $assignments);
        $filter->process($this->data);

        foreach ($filter->getMessages() as $key => $value)
        {
            $this->errors[$key] = $value;
        }

        if(!empty($this->errors))
        {
            return FALSE;
        }

        return TRUE;
    }

    public function checkTitle(){
        $tasks = $this->repository->fetchByOwner($this->user->getNick());

        foreach ($tasks as $task)
        {
            if($task->getTitle() === $this->data['title'])
                $this->task = $task;
        }

        if($this->task){
            return FALSE;
        }

        return TRUE;
    }

    // =======================================================================
    public function getProcessStatus():string{
        return $this->processStatus;
    }
    public function getErrors(): array{
        return $this->errors;
    }
    public function getTask(): Task{
        return $this->task;
    }
}