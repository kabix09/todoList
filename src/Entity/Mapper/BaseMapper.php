<?php
namespace App\Entity\Mapper;

use App\Entity\Base;
use App\Entity\Task;

abstract class BaseMapper implements EntityMapper
{
    public function createEntity(array $data){
        return static::convertArrayToEntity($data);
    }

    public function createEntityCollection(array $data){
        foreach ($data as $entity)
            yield $this->createEntity($entity);
    }

    abstract public static function targetClass() : string;
    abstract public static function convertArrayToEntity(array $data);
    abstract public static function convertEntityToArray(Base $objectInstance) : ?array;
    abstract public static function overwriteEntity(Base $sourceObject, Base $destinationObject): bool;


    protected static function arrayToEntity(array $data, Base $objectInstance) : ?Base
    {
        if($data){
            foreach ($objectInstance::MAPPING as $dbColumnName => $objectPropertyName){
                $method = "set" . ucfirst($objectPropertyName);
                if (isset($data[$dbColumnName]))    // jesli istnieje
                    $objectInstance->$method($data[$dbColumnName] ?? "");   // TODO ??? jesli istnieje ale nie am ustawionej wartosci -> ""
            }
            return $objectInstance;
        }
        return NULL;
    }

    protected static function entityToArray(Base $objectInstance) : ?array
    {
        $data = array();
        foreach ($objectInstance::MAPPING as $dbColumnName => $objectPropertyName){
            $method = "get" . ucfirst($objectPropertyName);
            $data[$dbColumnName] = $objectInstance->$method() ?? NULL;
        }

        return $data;
    }

    protected static function overwrite(Base $sourceObject, Base $destinationObject): void
    {
        foreach (get_class_methods(static::targetClass()) as $functionName)
        {
            if(strpos($functionName, "set") === 0)
            {
                $getter = "get" . ucfirst(substr($functionName, 3));
                $destinationObject->$functionName($sourceObject->$getter());
            }
        }
    }
}