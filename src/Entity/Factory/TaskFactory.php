<?php
namespace App\Entity\Factory;

use App\Entity\ { Task, Base};

class TaskFactory extends BaseFactory
{
    public static function arrayToEntity(array $data)
    {
        $name = self::targetClass();
        return parent::doArrayToEntity($data, new $name());
    }

    public static function entityToArray(Base $objectInstance): ?array{
        if($objectInstance instanceof Task)
            return parent::doEntityToArray($objectInstance);

        return NULL;
    }

    public static function targetClass(): string
    {
        return Task::class;
    }

    public function newTask(): Task{
        return new Task();
    }
}