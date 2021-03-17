<?php
namespace App\Entity\Mapper;

use App\Entity\ { Task, Base};

class TaskMapper extends BaseMapper
{
    public static function convertArrayToEntity(array $data)
    {
        $name = self::targetClass();
        return parent::arrayToEntity($data, new $name());
    }

    public static function convertEntityToArray(Base $objectInstance): ?array{
        if($objectInstance instanceof Task)
            return parent::entityToArray($objectInstance);

        return NULL;
    }

    public static function overwriteEntity($sourceObject, $destinationObject): bool
    {
        if(!$sourceObject instanceof Task || !$destinationObject instanceof Task)
            throw new \InvalidArgumentException("Arguments instance must be " . self::targetClass());

        parent::overwrite($sourceObject, $destinationObject);

        return TRUE;
    }

    public static function targetClass(): string
    {
        return Task::class;
    }
}