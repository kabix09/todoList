<?php
namespace App\Module\Access;

use App\Module\BaseActions;
use App\Module\Access\QueryParameters;
use ConnectionFactory\Connection;
use App\Repository\UserRepository;
use App\Service\Session\Session;

abstract class GenericAccess extends BaseActions implements \App\Module\Access\QueryParameters
{
    protected UserRepository $userRepository;

    public function __construct(Session $session, Connection $connection)
    {
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