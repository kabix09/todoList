<?php
namespace App\Access;

use App\Connection\Connection;
use App\Logger\Logger;
use App\Logger\MessageSheme;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;

abstract class BaseTaskAccess extends BaseAccess implements TaskParameters
{
    protected Logger $logger;
    private UserRepository $userRepository;
    protected TaskRepository $taskRepository;
    private UserManager $userManager;

    public function __construct(Session $session, Connection $connection)
    {
        $this->logger = new Logger;

        parent::__construct($session);

        $this->userRepository = new UserRepository($connection);
        $this->taskRepository = new TaskRepository($connection);
    }

    private function setUserTask(): void{
        if(empty($this->session['user']->getTaskCollection()))
            $this->userManager->getUserTasks($this->taskRepository);
    }

    private function getURLquery(): array
    {
        $queryData = [];
        foreach($_GET as $key => $value)
            $queryData[$key] = urldecode($value);

        return $queryData;
    }

    private function checkURLquery(array $queryParams): bool
    {
        if(count(static::QUERY_VARIABLES) !== count($queryParams))
            return FALSE;

        foreach (static::QUERY_VARIABLES as $value)
            if(!isset($queryParams[$value]))
                return FALSE;

        return TRUE;
    }

    private function checkUserTask(string $owner) : bool
    {
        return
            $owner === $this->session['user']->getNick();
    }

    private function checkTaskID(int $id) : bool
    {
        if(!in_array($id, array_map(function($element){
                return $element->getId();
            }, $this->session['user']->getTaskCollection())
        )) return FALSE;

        return TRUE;
    }

    public function checkAccess()
    {
        if(!$this->isLogged())
            $this->redirectToHome();
    }

    public function core()
    {
        $queryParams = $this->getURLquery();

        try{
            if(!empty(static::QUERY_VARIABLES))
            {
                if(!$this->checkURLquery($queryParams))
                    throw new \RuntimeException("script error - missing elements");

                if(!$this->checkUserTask($queryParams[static::QUERY_VARIABLES[static::OWNER]]))
                    throw new \RuntimeException("script error - incorrect user");

                $this->userManager = new UserManager($this->session['user'], $this->userRepository);
                $this->setUserTask();

                if(!$this->checkTaskID($queryParams[static::QUERY_VARIABLES[static::ID]]))
                    throw new \RuntimeException("incorrect task - this user has no such task");
            }

            // session operation - when we have sure that request is correct
            $this->sessionManage();

            $this->main($queryParams);
        }catch (\Exception $e) {
            $config = new MessageSheme($this->session['user']->getNick(),
                static::class,
                __FUNCTION__,
                TRUE);
            $this->logger->error($e->getMessage(), [$config]);

            $this->redirectToHome();
        }
    }

    abstract protected function main(array $queryParams): void;
}