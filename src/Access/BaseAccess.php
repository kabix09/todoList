<?php
namespace App\Access;

abstract class BaseAccess extends Access
{
    public function core(){}

    abstract protected function main(array $queryParams);
}