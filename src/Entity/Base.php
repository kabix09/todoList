<?php
namespace App\Entity;

class Base
{
    protected $id;
    const MAPPING = ["id" => "id"];
    const DATE_FORMAT = "Y-m-d H:i:s";

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = (int) $id;
    }

    public static function getColumnFieldName(string $entityFieldName) : ?string{
        foreach (static::MAPPING as $dbColumnName => $objectPropertyName)
            if($entityFieldName === $objectPropertyName)
                return $dbColumnName;

        return NULL;
    }

    public static function arrayToEntity(array $data, Base $objectInstance) : ?Base
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

    public static function entityToArray(Base $objectInstance) : ?array
    {
        $data = array();
        foreach ($objectInstance::MAPPING as $dbColumnName => $objectPropertyName){
            $method = "get" . ucfirst($objectPropertyName);
            $data[$dbColumnName] = $objectInstance->$method() ?? NULL;
        }
        return $data;
    }
}