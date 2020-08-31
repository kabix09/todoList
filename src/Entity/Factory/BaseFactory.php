<?php
namespace App\Entity\Factory;

use App\Entity\Base;

abstract class BaseFactory implements EntityFactory
{
    public function createEntity(array $data){
        return $this::arrayToEntity($data);
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
                $objectInstance->$method($data[$dbColumnName]);
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