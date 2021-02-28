<?php
namespace App\Repository;

use ConnectionFactory\Connection;
use App\Service\Connection\QueryBuilder;
use App\Entity\Base;
use App\Entity\Mapper\BaseMapper;
use ConnectionFactory\Validator\ConnectionDriverValidator;

abstract class BaseRepository implements Repository
{
    protected $connection;
    protected $entityFactory;
    protected $dbName = "";

    public function __construct(Connection $connection, string $dbName, BaseMapper $baseFactory){
        $this->connection = $connection;
        $this->dbName = $dbName;
        $this->entityFactory = $baseFactory;

        $this->connection->connect(new ConnectionDriverValidator());
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

        $this->clearEmptyValue($base);

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

        $this->clearIdField($base);

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
    protected function buildCriteria(array $criteria = array())
    {
        if(!empty($criteria))
        {
            foreach ($criteria as $sqlComand => $value){
                $command = strtolower($sqlComand);

                if(is_array($value))
                {
                    // if any value in MAPPER dosn't match then parameter is value not column name
                    $firstPram = $this->entityFactory::targetClass()::getColumnFieldName($value[0]);

                    if(!is_array($value[1]))
                        $secondPram = $this->entityFactory::targetClass()::getColumnFieldName($value[1]);

                    QueryBuilder::$command(
                        $firstPram ?? $value[0],
                        $secondPram ?? $value[1]);
                }
                else
                    QueryBuilder::$command($value);
            }
        }
    }

    private function clearEmptyValue(array &$data)
    {
        foreach ($data as $key => $value)
        {
            if(is_null($value) || empty($value))
                unset($data[$key]);
        }
    }

    private function clearIdField(array &$data){
        $keys = array_keys($data);
        foreach ($keys as $number => $value)
        {
            if($value === "id")
                unset($data["id"]);
        }
    }
}