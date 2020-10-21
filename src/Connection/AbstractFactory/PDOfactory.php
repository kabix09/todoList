<?php declare(strict_types = 1);
namespace App\Connection\AbstractFactory;

use App\Connection\ConnectionInterface;

abstract class PDOfactory implements ConnectionInterface
{
    public abstract function connect(array $connectData = array());

    public function makeDns(array $dnsData): string
    {
        $dns = $dnsData['driver'] . ':';
        unset($dnsData['driver']);

        foreach ($dnsData as $key => $value){
            $dns .= $key . '=' . $value . ';';
        }
        return substr($dns, 0, -1);
    }
}