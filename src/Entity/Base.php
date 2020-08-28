<?php
namespace App\Entity;

class Base
{
    protected $id;
    protected array $mapping = ["id" => "id"];
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

    public static function arrayToEntity(array $data, Base $objectInstance) : ?Base
    {
        if($data){
            foreach ($objectInstance->mapping as $dbColumnName => $objectPropertyName){
                $method = "set" . ucfirst($objectPropertyName);
                $objectInstance->$method($data[$dbColumnName]);
            }
            return $objectInstance;
        }
        return NULL;
    }

    public function entityToArray() : ?array
    {
        $data = array();
        foreach ($this->mapping as $dbColumnName => $objectPropertyName){
            $method = "get" . ucfirst($objectPropertyName);
            $data[$dbColumnName] = $this->$method() ?? NULL;
        }
        return $data;
    }
}