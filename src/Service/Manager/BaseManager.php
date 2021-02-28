<?php
namespace App\Service\Manager;

use App\Entity\Base;
use App\Repository\BaseRepository;

abstract class BaseManager
{
    protected $repository;
    protected $object;

    public function __construct(?BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    abstract protected function setObject($data);
    abstract public function return();
    abstract public function update();


    protected function doUpdate(array $criteria): bool {
        return $this->repository->update($this->object, $criteria);
    }

    protected function getDate($date = NULL) : string {
        return
            (new \DateTime())->format(Base::DATE_FORMAT);
    }

    // ---- function to clone object values
    public function set(Base $copiedObject, string $className){
        foreach (get_class_methods($className) as $functionName)
        {
            if(strpos($functionName, "set") === 0)
            {
                $getter = "get" . ucfirst(substr($functionName, 3));
                $this->object->$functionName($copiedObject->$getter());
            }
        }
    }
}