<?php
namespace App\Access;

use App\Connection\Connection;
use App\Entity\User;
use App\Logger\Logger;
use App\Logger\MessageSheme;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Session\SessionManager;

abstract class BaseTaskScript
{
    protected const ID = "ID";
    protected const OWNER = "OWNER";
    protected const QUERY_VARIABLES = [self::ID => "id", self::OWNER => "owner"];

    protected Logger $logger;
    protected Session $session;
    private UserRepository $userRepository;
    protected TaskRepository $taskRepository;
    private UserManager $userManager;

    public function __construct(Session $session, Connection $connection)
    {
        $this->session = $session;
        $this->logger = new Logger;

        $this->userRepository = new UserRepository($connection);
        $this->taskRepository = new TaskRepository($connection);
    }

    private function isLogged() : bool
    {
        return
            isset($this->session['user']) && $this->session['user'] instanceof User;
    }

    private function setUserTask(): void{
        if(empty($this->session['user']->getTaskCollection()))
            $this->userManager->getUserTasks($this->taskRepository);
    }

    private function getURLquery(): ?array
    {
        return $_GET;
    }

    private function checkURLquery(array $queryParams): bool
    {
        if(count(static::QUERY_VARIABLES) !== count($queryParams))
            return FALSE;

        foreach (self::QUERY_VARIABLES as $value)
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

    private function sessionManage(): void
    {
        $sessionManager = new SessionManager($this->session);
        if(!$sessionManager->manage())
        {
            // logout and redirect to login page
            die("session error - try to refresh page :/"); // TODO - fix error and behaviour
        }
    }

    protected function redirectToHome(): void
    {
        header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
        exit();
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
            if(!$this->checkURLquery($queryParams))
                throw new \RuntimeException("script error - missing elements");

            if(!$this->checkUserTask($queryParams[self::QUERY_VARIABLES[self::OWNER]]))
                throw new \RuntimeException("script error - incorrect user");

            $this->userManager = new UserManager($this->session['user'], $this->userRepository);
            $this->setUserTask();

            if(!$this->checkTaskID($queryParams[self::QUERY_VARIABLES[self::ID]]))
                throw new \RuntimeException("incorrect task - this user has no such task");

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