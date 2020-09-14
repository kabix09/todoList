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
    public function getId(): ?int
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
}