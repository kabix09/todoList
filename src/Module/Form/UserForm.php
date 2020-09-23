<?php
namespace App\Module\Form;

use App\Connection\Connection;
use App\Entity\User;
use App\Repository\UserRepository;

abstract class UserForm extends FormGeneric
{
    private const LOGIN_ERROR = "";
    private const PASSWORD_ERROR = "";

    public function __construct(array $formData, Connection $connection)
    {
        parent::__construct($formData,
                            new UserRepository($connection));
    }

    // user form module methods
    protected function checkNick(): bool
    {
        $this->object =  $this->repository->fetchByNick($this->data['nick']);

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