<?php
namespace App\Connection;

interface ConnectionInterface
{
    public function connect(array $connectData = array());
}