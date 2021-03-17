<?php declare(strict_types=1);
namespace App\Module\FormHandling\Task;

use App\Entity\Mapper\TaskMapper;
use App\Service\EntityManager\Task\Builder\TaskBuilder;
use ConnectionFactory\Connection;
use App\Entity\Task;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\Task\TaskManager;
use App\Module\FormHandling\Task\TaskForm;
use App\Repository\UserRepository;

class Send extends TaskForm
{
    private UserRepository $userRepository;

    public function __construct(array $formData, Connection $connection, Task $task)
    {
        parent::__construct($formData, $connection);

        $this->userRepository = new UserRepository($connection);

        $this->object = $task;
    }

    protected function doHandler()
    {
        // check new owner
        if(!$this->checkNewNick($this->data['new_owner']))
        {
            $this->errors['new_owner'] = ["this user doesn't exist"];
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if($this->processStatus === NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();


            // update owner in task
            $this->prepareTask($this->object);

            // update existing task
            if(!$this->repository->update($this->object, [
                "WHERE" => NULL,
                "AND" => ["title='{$this->object->getTitle()}'","author='{$this->object->getAuthor()}'"]
            ]))
            {
                throw new \RuntimeException("couldn't send task id: {$this->object->getId()} to {$this->object->getOwner()} :/");
            }else
            {
                $config = new MessageSheme($this->object->getAuthor(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->info("Successfully sended task with id: {$this->object->getId()} to {$this->object->getOwner()}", [$config]);

                $config = new MessageSheme($this->object->getOwner(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->info("Successfully received task with id: {$this->object->getId()} from {$this->object->getAuthor()}", [$config]);
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }else
        {
            $config = new MessageSheme($this->object->getAuthor(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->error("An attempt to send task with id: {$this->object->getId()} to {$this->data['new_owner']} has failed", [$config]);
        }
    }

    protected function prepareTask(Task $overwrittenObject): bool
    {
        $taskBuilder = new TaskBuilder(TaskMapper::convertArrayToEntity($this->data));
        $taskBuilder->setOwner($this->data['new_owner']);

        return TaskMapper::overwriteEntity($taskBuilder->getInstance(), $overwrittenObject);
    }

    private function checkNewNick(string $newNick): bool
    {
        return in_array($newNick,
            array_map(function($element) {
                return $element["nick"];
            }, $this->userRepository->getNickList()));
    }
}