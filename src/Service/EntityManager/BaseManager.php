<?php
namespace App\Service\EntityManager;

use App\Entity\Base;
use App\Repository\BaseRepository;
use App\Service\EntityManager\Session\Builder\BaseFactory;
use App\Service\EntityManager\IEntityBuilder;

abstract class BaseManager
{
    protected $repository;
    protected $objectBuilder;
    protected $object;

    public function __construct(?\App\Service\EntityManager\IEntityBuilder $objectBuilder, ?BaseRepository $repository)
    {
        $this->repository = $repository;
        $this->objectBuilder = $objectBuilder;

        $this->object = ($this->objectBuilder) ? $this->objectBuilder->getInstance(): NULL;     // TODO - why that?
    }

    abstract public function return();

    public function prepareInstance(array $config) : void
    {
        foreach ($config as $action => $value)
        {
            $methodName = "set" . ucfirst($action);
            $this->objectBuilder->$methodName($value);
        }
    }

    public function update(array $criteria=[]) : bool
    {
        return $this->repository->update($this->objectBuilder->getInstance(), $criteria);
    }

    protected function getDate($date = NULL) : string {
        return
            (new \DateTime())->format(Base::DATE_FORMAT);
    }
}