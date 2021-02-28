<?php
namespace App\Module\Access;

use App\Module\Access\GenericAccess;
use App\Entity\User;
use App\Service\Logger\MessageSheme;

abstract class BaseUserAccess extends GenericAccess
{
    public const EMAIL = 1;
    public const NICK = 2;
    public const KEY = 3;
    public const QUERY_PARAMETERS = [self::EMAIL => 'email', self::NICK => 'nick', self::KEY => 'key'];

    protected $user;

    public function core()
    {
        $queryParams = $this->getQueryParameters();

        try {

            if(!$this->checkQueryParameters($queryParams))
                throw new \RuntimeException("script error - missing elements");

            $this->user = $this->userRepository->fetchByNick($queryParams[static::QUERY_PARAMETERS[static::NICK]]);

            if(! $this->user instanceof User)
                throw new \Exception("account with that nick doesn't exists");

            if($this->user->getEmail() !== $queryParams[static::QUERY_PARAMETERS[static::EMAIL]])
                throw new \Exception("user's email doesn't match to passed value");

            $this->main($queryParams);

        }catch (\Exception $e) {
            $config = new MessageSheme( $this->user ? $this->user->getNick() : $_SERVER['REMOTE_ADDR'],
                static::class,
                __FUNCTION__,
                TRUE);
            $this->logger->error($e->getMessage(), [$config]);

            $this->redirectToHome();
        }
    }
}