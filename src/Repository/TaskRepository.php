<?php
namespace App\Repository;
use App\Connection\Connection;
use App\Connection\QueryBuilder;
use App\Entity\Factory\TaskFactory;
use App\Entity\Task;

class TaskRepository extends BaseRepository
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, Task::TABLE_NAME, new TaskFactory());
    }

    public function find(array $columns = array(), array $criteria = array())
    {
        return $this->entityFactory->createEntityCollection(
            parent::find($columns, $criteria)
        );
    }

    public function insert($base): bool
    {
        return parent::insert(
            $this->entityFactory::entityToArray($base)
        );
    }

    public function update($base, array $criteria = array()): bool
    {
        return parent::update(
            $this->entityFactory::entityToArray($base),
            $criteria
        );
    }

    public function remove(array $criteria = array()): bool{
        return parent::remove($criteria);
    }

    public function fetchById(int $id){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("id = :id")->getSQL()
        );

        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntity($result);
        else
            return NULL;
    }

    public function fetchByTitle(string $title){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("title = :title")->getSQL()
        );

        $statement->bindValue(':title', $title, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByCreateDate(){}

    public function fetchByAuthor(string $author){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("author = :author")->getSQL()
        );

        $statement->bindValue(':author', $author, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByOwner(string $owner){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("owner = :owner")->getSQL()
        );

        $statement->bindValue(':owner', $owner, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByTargetEndDate(){}

    public function fetchByStatus(string $status){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("status = :status")::getSQL()
        );

        $statement->bindValue(':status', $status, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }
}