<?php
namespace App\Module\Access;

use App\Module\Access\GenericAccess;
use App\Service\EntityManager\User\Builder\UserBuilder;
use ConnectionFactory\Connection;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\User\UserManager;
use App\Repository\TaskRepository;
use App\Service\Session\Session;

abstract class BaseTaskAccess extends GenericAccess
{
    public const ID = 1;
    public const OWNER = 2;
    public const QUERY_PARAMETERS = [self::ID => 'id', self::OWNER => 'owner'];

    protected TaskRepository $taskRepository;
    private UserManager $userManager;

    public function __construct(Session $session, Connection $connection)
    {
        parent::__construct($session, $connection);

        $this->taskRepository = new TaskRepository($connection);
    }

    private function setUserTask(): void{
        if(empty($this->session['user']->getTaskCollection())) {
            $this->userManager->loadUserTasks($this->session['user']->getNick());
            $this->session['user'] = $this->userManager->return();
        }
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



    public function core()
    {
        $queryParams = $this->getQueryParameters();

        try{
            if(!empty(static::QUERY_PARAMETERS))
            {
                if(!$this->checkQueryParameters($queryParams))
                    throw new \RuntimeException("script error - missing elements");

                if(!$this->checkUserTask($queryParams[static::QUERY_PARAMETERS[static::OWNER]]))
                    throw new \RuntimeException("script error - incorrect user");

                $this->userManager = new UserManager(
                    new UserBuilder(
                        $this->session['user']
                    ),
                    $this->userRepository,
                    $this->taskRepository
                );
                $this->setUserTask();

                if(!$this->checkTaskID($queryParams[static::QUERY_PARAMETERS[static::ID]]))
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