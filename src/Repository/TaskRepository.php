<?php
namespace App\Repository;
use ConnectionFactory\Connection;
use App\Service\Connection\QueryBuilder;
use App\Entity\Mapper\TaskMapper;
use App\Entity\Task;

class TaskRepository extends BaseRepository
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, Task::TABLE_NAME, new TaskMapper());
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
            $this->entityFactory::convertEntityToArray($base)
        );
    }

    public function update($base, array $criteria = array()): bool
    {
        return parent::update(
            $this->entityFactory::convertEntityToArray($base),
            $criteria
        );
    }

    public function remove(array $criteria = array()): bool{
        return parent::remove($criteria);
    }

    public function fetchById(int $id): ?Task{
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

    public function fetchByTitle(string $title): ?\Generator{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("title = :title")->getSQL()
        );

        $statement->bindValue(':title', $title, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByCreateDate(): ?\Generator{}

    public function fetchByAuthor(string $author): ?\Generator{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("author = :author")->getSQL()
        );

        $statement->bindValue(':author', $author, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByOwner(string $owner): ?\Generator{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("owner = :owner")->getSQL()
        );

        $statement->bindValue(':owner', $owner, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByTargetEndDate(): ?\Generator{}

    public function fetchByStatus(string $status): ?\Generator{
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