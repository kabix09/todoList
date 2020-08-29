<?php
namespace App\Repository;

use App\Entity\Base;

interface Repository
{
    public function find (array $columns = array(), array $criteria = array());
    public function insert ($base) : bool;
    public function update ($base, array $criteria = array()): bool;
    public function remove (array $criteria = array()) : bool;
}