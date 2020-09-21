<?php
namespace App\Module\Task;

use App\Connection\Connection;
use App\Entity\Task;
use App\Filter\Filter;
use App\Manager\TaskManager;
use App\Module\Observer\Observable;
use App\Module\Observer\Observer;
use App\Repository\TaskRepository;
use App\Token\Token;

class Edit implements Observable
{
    const PROCESS_STATUS = ["errors", "correct"];
    private array $observers = [];

    private array $data;
    private $task;
    private array $errors = [];

    private $processStatus = NULL;
    /**
     * @var TaskRepository
     */
    private TaskRepository $repository;

    public function __construct(array $formData, array $dbConfig)
    {
        $this->data = $formData;
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
    public function taskHandler(?string &$serverToken = NULL, array $filter, array $assignments): bool{
        try{

            if ($this->checkToken($serverToken)) {

                if (!$this->validData($filter, $assignments))
                {
                    $this->processStatus = self::PROCESS_STATUS[0];
                }

                if($this->processStatus === NULL)
                {
                        // set other data
                    $taskManager = new TaskManager($this->data, $this->repository);
                    $taskManager->setStatus();

                    $this->task = $taskManager->return();

                        // update existing task
                    if(!$this->repository->update($this->task, [
                                                    "WHERE" => NULL,
                                                    "AND" => ["id='{$this->task->getId()}'","owner='{$this->task->getOwner()}'"]
                                                ]))
                    {
                        throw new \RuntimeException("system error - couldn't update task id: {$this->task->getId()} :/");
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
    public function checkToken(?string &$serverToken = NULL): bool{
        if(!isset($serverToken))
            throw new \RuntimeException("token doesn't exists on server side ://");

        if(sodium_compare(
                (new Token($serverToken))->hash()->getToken(),
                (new Token($this->data['hidden']))->decode()->getToken()
            ) !== 0
        ) throw new \RuntimeException('detected cross-site attack on login form');

        unset($this->data['hidden']);
        unset($serverToken);    // doesn't work --- WHY ???

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