<?php
namespace App\Access;

use App\Connection\Connection;
use App\Logger\Logger;
use App\Repository\UserRepository;
use App\Session\Session;

abstract class GenericAccess extends BaseAccess implements QueryParameters
{
    protected Logger $logger;
    protected UserRepository $userRepository;

    public function __construct(Session $session, Connection $connection)
    {
        $this->logger = new Logger();
        $this->userRepository = new UserRepository($connection);

        parent::__construct($session);
    }

    protected function getQueryParameters(): array
    {
        $queryData = [];
        foreach($_GET as $key => $value)
            $queryData[$key] = urldecode($value);

        return $queryData;
    }

    protected function checkQueryParameters(array $queryParams): bool
    {
        if(count(static::QUERY_PARAMETERS) !== count($queryParams))
            return FALSE;

        foreach (static::QUERY_PARAMETERS as $value)
            if(!isset($queryParams[$value]))
                return FALSE;

        return TRUE;
    }

    public function checkAccess()
    {
        if(!$this->isLogged())
            $this->redirectToHome();
    }
}