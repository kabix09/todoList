<?php
namespace App\Module\Form\Login;

use App\Module\Form\UserForm;

final class Login extends UserForm
{
    protected const LOGIN_ERROR = "incorrect login";
    protected const PASSWORD_ERROR = "incorrect password";

    protected function doHandler()
    {
        if (!$this->checkNick())
        {
            $this->errors['nick'][] = self::LOGIN_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        } elseif (!$this->checkPassword())
        {
            $this->errors['password'][] = self::PASSWORD_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if ($this->processStatus === NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            $this->processStatus = self::PROCESS_STATUS[1];
        }
    }

    protected function checkPassword(): bool
    {
        if(!password_verify(
                $this->data['password'],
                $this->object->getPassword()
            )){
            return FALSE;
        }
        return TRUE;
    }
}