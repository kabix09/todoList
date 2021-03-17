<?php
namespace App\Module\FormHandling\User;

use App\Module\FormHandling\FormGeneric;
use ConnectionFactory\Connection;
use App\Entity\User;
use App\Repository\UserRepository;

abstract class UserForm extends FormGeneric
{
    protected const LOGIN_ERROR = "";
    protected const PASSWORD_ERROR = "";

    public function __construct(array $formData, Connection $connection)
    {
        parent::__construct($formData,
                            new UserRepository($connection));

        $this->object = new User();
    }

    // user form module methods
    protected function checkNick(): bool
    {
        $this->object = $this->repository->fetchByNick($this->data['nick']);

        if(! $this->object )
        {
            return FALSE;
        }

        return TRUE;
    }

    abstract protected function checkPassword(): bool;


    public function getObject(bool $flag = FALSE): User{
        if($flag)
            $this->object = $this->repository->fetchByNick($this->object->getNick());

        return $this->object;
    }
}