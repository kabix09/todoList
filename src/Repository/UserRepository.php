<?php
namespace App\Repository;
use App\Connection\Connection;
use App\Connection\QueryBuilder;
use App\Entity\Mapper\UserMapper;
use App\Entity\User;

final class UserRepository extends BaseRepository
{

    public function __construct(Connection $connection)
    {
        parent::__construct($connection, User::TABLE_NAME, new UserMapper());
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

    public function fetchByNick(string $nick) : ?User{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("nick = :nick")::getSQL()
        );

        $statement->bindValue(':nick', $nick, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntity($result);
        else
            return NULL;
    }

    public function fetchByEmail(string $email) : ?User{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("email = :email")::getSQL()
        );

        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntity($result);
        else
            return NULL;
    }

    public function fetchByLastLoginDate(){}

    public function fetchByCreateAccountDate(){}

    public function fetchByStatus(string $status){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("account_status = :status")::getSQL()
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

    public function fetchByKey(string $key){
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("account_key = :account_key")::getSQL()
        );

        $statement->bindValue(':account_key', $key, \PDO::PARAM_STR);
        $statement->execute();

        if($statement->rowCount() === 1)
        {
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if($result)
                return $this->entityFactory->createEntity($result);
        }

        return NULL;
    }
}