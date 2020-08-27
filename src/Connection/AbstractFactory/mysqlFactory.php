<?php declare(strict_types = 1);
namespace App\Connection\AbstractFactory;

use PDO;

class mysqlFactory extends  PDOfactory
{
    public function connect(array $connectData)
    {
        /* ["host" => $connectData["host"], "dbname" => $connectData["dbname"], "charset" => $connectData['charset'] ?? "utf8"] */

        $dns = $this->makeDns(array_merge(
                                array_splice($connectData, 0, 3),
                                ["charset" => $connectData['charset'] ?? "utf8"])
                            );

        try{
            return new PDO($dns,
                            $connectData['user'], $connectData['password'],
                            $connectData['options'] ??  NULL);
        }catch (\PDOException $e){
            error_log($e->getMessage());
        }catch (\Throwable $e){
            throw new \RuntimeException("unable to connect: ".$e->getMessage());
        }
    }
}