<?php
namespace App\Entity\Mapper;

use App\Entity\Base;
use App\Entity\Task;

abstract class BaseMapper implements EntityMapper
{
    public function createEntity(array $data){
        return static::arrayToEntity($data);
    }

    public function createEntityCollection(array $data){
        foreach ($data as $entity)
            yield $this->createEntity($entity);
    }

    abstract public static function targetClass() : string;


    abstract public static function arrayToEntity(array $data);

    protected static function doArrayToEntity(array $data, Base $objectInstance) : ?Base
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


    abstract public static function entityToArray(Base $objectInstance) : ?array;

    protected static function doEntityToArray(Base $objectInstance) : ?array
    {
        $data = array();
        foreach ($objectInstance::MAPPING as $dbColumnName => $objectPropertyName){
            $method = "get" . ucfirst($objectPropertyName);
            $data[$dbColumnName] = $objectInstance->$method() ?? NULL;
        }

        return $data;
    }
}