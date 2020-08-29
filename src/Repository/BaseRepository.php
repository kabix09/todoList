<?php
namespace App\Repository;

use App\Connection\Connection;
use App\Connection\QueryBuilder;
use App\Entity\Base;

abstract class BaseRepository implements Repository
{
    protected $connection;
    protected $dbName = "";

    public function __construct(Connection $connection, string $dbName){
        $this->connection = $connection;
        $this->dbName = $dbName;

        $this->connection->connect();
    }

    /*
     * criteria:
     * name - sql function
     * value - value or array values, an example in OR gets 2 parameters
     */
    /* toDo -> use bindValue in $criteria */
    public function find(array $columns = array(), array $criteria = array())
    {
        QueryBuilder::select($this->dbName, $columns);

        $this->buildCriteria($criteria);

        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::getSQL()
        );

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert($base): bool
    {
        if(!is_array($base))
            return FALSE;

       $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::insert($this->dbName, array_keys($base))::getSql()
        );

        foreach ($base as $key => $value){
            $statement->bindValue(":" . $key, $value, \PDO::PARAM_STR);
        }

        if($statement->execute())   //var_dump($statement->errorInfo());
            return TRUE;
        else
            return FALSE;
    }

    public function update($base, array $criteria = array()): bool
    {
        if(!is_array($base))
            return FALSE;

        QueryBuilder::update($this->dbName)->set(array_keys($base));

        $this->buildCriteria($criteria);

        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::getSQL()
        );

        foreach ($base as $key => $value){
            $statement->bindValue(":" . $key, $value, \PDO::PARAM_STR);
        }

        if($statement->execute())   //var_dump($statement->errorInfo());
            return TRUE;
        else
            return FALSE;
    }

    /* toDo -> use bindValue in $criteria */
    public function remove(array $criteria = array()): bool
    {
        QueryBuilder::remove($this->dbName);

        $this->buildCriteria($criteria);

        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::getSQL()
        );

        if($statement->execute())   //var_dump($statement->errorInfo());
            return TRUE;
        else
            return FALSE;
    }

    /* toDo -> use bindValue in $criteria */
    abstract protected function buildCriteria(array $criteria = array());
}