<?php
namespace App\Module;

use App\Module\Access;

abstract class BaseActions extends Access
{
    public function core(){}

    abstract protected function main(array $queryParams);
}