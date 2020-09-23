<?php
namespace App\Module\Form;

use App\Entity\User;

abstract class PasswordForm extends UserForm
{
    protected const PASSWORD_ERROR = "passwords must be the same";

    public function checkPassword(): bool{
        if($this->data['password'] !== $this->data['repeatPassword']) {
            return FALSE;
        }

        return TRUE;
    }
}