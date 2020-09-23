<?php
namespace App\Module\Form\Register;

use App\Entity\User;
use App\Manager\UserManager;
use App\Module\Form\UserForm;

final class Register extends UserForm
{
    private const LOGIN_ERROR = "this login already exists";
    private const PASSWORD_ERROR = "passwords must be the same";

    protected function doHandler()
    {
        if ($this->checkNick())
        {
            $this->errors['nick'][] = self::LOGIN_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        } elseif (!$this->checkPassword()){
            $this->errors['repeatPassword'][] = self::PASSWORD_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if ($this->processStatus === NULL)
        {
            unset($this->data['repeatPassword']);
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            // prepare object
            $this->object = $this->prepareUserObject();

            // insert new user
            if(!$this->repository->insert($this->object))
            {
                throw new \RuntimeException("couldn't create new user :/");
            }

            // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }
    }

    protected function checkPassword(): bool
    {
        if($this->data['password'] !== $this->data['repeatPassword']) {
            return FALSE;
        }

        return TRUE;
    }


    private function prepareUserObject() : User
    {
        $userManager = new UserManager($this->data, $this->repository);
            // hash password
        $userManager->hashPassword($this->data['password']);

        return $userManager->return();
    }
}