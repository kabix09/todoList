<?php
namespace App\Entity\Factory;

interface EntityFactory
{
    public function createEntity(array $data);
    public function createEntityCollection(array $data); // return generator
}