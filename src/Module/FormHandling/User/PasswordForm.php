<?php
namespace App\Module\FormHandling\User;

use App\Entity\User;
use App\Module\FormHandling\User\UserForm;

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