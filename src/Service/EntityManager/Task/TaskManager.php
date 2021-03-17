<?php
namespace App\Service\EntityManager\Task;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\EntityManager\BaseManager;
use App\Service\EntityManager\Task\Builder\TaskBuilder;

final class TaskManager extends BaseManager
{
    public function __construct(TaskBuilder $taskBuilder, TaskRepository $taskRepository)
    {
        parent::__construct($taskBuilder, $taskRepository);
    }

    public function update(array $criteria=[]): bool
    {
        return parent::update([
            "where" => ["id", "= '{$this->object->getId()}'"]
        ]);
    }

    public function return(): Task
    {
        return $this->objectBuilder->getInstance();
    }

    // TODO - one function: edit - to settup all property at once

    /*
     * status:
     * 1) start data > current data || start data == null -> planned
     * 2) start data <= current data && (finsh data <= current data || finish data == null) -> started
     * 3) finish data < current data -> finished
     */
    public function manageStatus(): void {
        if(is_null($this->object->getStartDate()))
        {
            $this->objectBuilder->setStatus("prepared");
        }else
        {
            if((new \DateTime($this->object->getStartDate()))->getTimestamp() > (new \DateTime())->getTimestamp())
            {
                $this->objectBuilder->setStatus("planned");
            }else
            {
                if(is_null($this->object->getTargetEndDate()) ||  (new \DateTime($this->object->getTargetEndDate()))->getTimestamp() <= (new \DateTime())->getTimestamp())
                {
                    $this->objectBuilder->setStatus("finished");
                }else{
                    $this->objectBuilder->setStatus("started");
                }
            }
        }
    }
}
