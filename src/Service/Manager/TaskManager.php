<?php
namespace App\Service\Manager;

use App\Entity\Mapper\TaskMapper;
use App\Entity\Task;
use App\Repository\TaskRepository;

final class TaskManager extends BaseManager
{
    public function __construct($data, TaskRepository $taskRepository)
    {
        $this->setObject($data);

        parent::__construct($taskRepository);
    }

    protected function setObject($data)
    {
        if(is_array($data))
            $this->object = TaskMapper::arrayToEntity($data);
        elseif($data instanceof Task)
            $this->object = $data;
        else
            $this->object = new Task();
    }

    public function return(): Task{
        return $this->object;
    }

    public function update(): bool
    {
        return $this->doUpdate([
            "where" => ["id", "= '{$this->object->getId()}'"]
        ]);
    }

    // ---- base entity config functions ----
    public function setCreateDate(): void
    {
        $this->object->setCreateDate(
            $this->getDate()
        );
    }
    public function setAuthor(string $author, bool $flag = FALSE): void {
        $this->object->setAuthor($author);

        if($flag)
            ($task ?? $this->object)->setOwner($author);
    }
    public function setOwner(string $newOwner): void{
        $this->object->setOwner($newOwner);
    }

    /*
     * status:
     * 1) start data > current data || start data == null -> planned
     * 2) start data <= current data && (finsh data <= current data || finish data == null) -> started
     * 3) finish data < current data -> finished
     */
    public function setStatus(): void {
        if(is_null($this->object->getStartDate()))
        {
            $this->object->setStatus("prepared");
        }else
        {
            if((new \DateTime($this->object->getStartDate()))->getTimestamp() > (new \DateTime())->getTimestamp())
            {
                $this->object->setStatus("planned");
            }else
            {
                if(is_null($this->object->getTargetEndDate()) ||  (new \DateTime($this->object->getTargetEndDate()))->getTimestamp() <= (new \DateTime())->getTimestamp())
                {
                    $this->object->setStatus("finished");
                }else{
                    $this->object->setStatus("started");
                }
            }
        }
    }

    // ---- action functions
    public function changeOwner(string $newOwner): bool{
        $this->object->setOwner($newOwner);

        return $this->update();
    }

    public function changeStatus(string $newStatus): bool {
        if(!$this->validStatus($newStatus))
            return FALSE;

        $this->object->setStatus($newStatus);

        return $this->update();
    }

    // ---- ---- support functions
    private function validStatus(string $newStatus) : bool{
        if(!in_array($newStatus, Task::STATUS))
            throw new \RuntimeException("illegal status");

        return TRUE;
    }

    public function toArray(): array {
        return TaskMapper::entityToArray($this->object);
    } // is it really necessary??
}