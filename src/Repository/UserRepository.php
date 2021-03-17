<?php
namespace App\Repository;
use ConnectionFactory\Connection;
use App\Service\Connection\QueryBuilder;
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

    public function fetchByEmail(string $email): ?\Generator{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName)->where("email = :email")::getSQL()
        );

        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if($result)
            return $this->entityFactory->createEntityCollection($result);
        else
            return NULL;
    }

    public function fetchByLastLoginDate(): ?\Generator{}

    public function fetchByCreateAccountDate(): ?\Generator{}

    public function fetchByStatus(string $status): ?\Generator{
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

    public function fetchByKey(string $key): ?User{
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

    public function getNickList(): ?array{
        $statement = $this->connection->getConnection()->prepare(
            QueryBuilder::select($this->dbName, ["nick"])::getSQL()
        );

        $statement->execute();

        if($statement->rowCount() != 0)
        {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $statement->closeCursor();

            return $result;
        }

        return NULL;
    }
}