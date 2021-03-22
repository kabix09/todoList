<?php

namespace App\Repository;

use ConnectionFactory\Connection;
use App\Service\Connection\QueryBuilder;
use App\Entity\Mapper\SessionMapper;
use App\Entity\Session;

class SessionRepository extends BaseRepository
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, Session::TABLE_NAME, new SessionMapper());
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

    //--------------------------------------------------------------
    public function fetchById(int $id): ?Session{
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

    public function fetchBySessionKey(string $sessionKey): ?Session{      // must be only one result
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("session_key = :session_key")->getSQL()
        );

        $statement->bindValue(':session_key', $sessionKey, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntity($result);
        else
            return NULL;
    }

    public function fetchByUserIP(string $userIP): ?\Generator{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("user_ip = :user_ip")->getSQL()
        );

        $statement->bindValue(':user_ip', $userIP, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByUserNick(?string $userNick = NULL): ?\Generator
    {
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("user_nick = :user_nick")->getSQL()
        );

        $statement->bindValue(':user_nick', $userNick, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    //--------------------------------------------------------------
    public function fetchCurrentSession(): ?Session
    {

    }

}