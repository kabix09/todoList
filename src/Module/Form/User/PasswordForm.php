<?php
namespace App\Module\Form\User;

use App\Entity\User;
use App\Module\Form\User\UserForm;

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