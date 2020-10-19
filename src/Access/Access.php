<?php
namespace App\Access;

use App\Entity\User;
use App\Session\Session;

class Access
{
    public const ID = "ID";
    public const OWNER = "OWNER";
    public const QUERY_VARIABLES = [self::ID => "id", self::OWNER => "owner"];

    protected Session $session;
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    protected function isLogged() : bool
    {
        return
            isset($this->session['user']) && $this->session['user'] instanceof User;
    }


}