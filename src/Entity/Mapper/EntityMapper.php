<?php
namespace App\Entity\Mapper;

interface EntityMapper
{
    public function createEntity(array $data);
    public function createEntityCollection(array $data); // return generator
}