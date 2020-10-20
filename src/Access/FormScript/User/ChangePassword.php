<?php
namespace App\Access\FormScript\User;

use App\Access\BaseFormAccess;
use App\Connection\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Password\ChangePwd;
use App\Module\SessionObserver;

class ChangePassword extends BaseFormAccess
{
    protected function clearErrors(): void
    {
        if(isset($session['changepwdErrors']))
            unset($session['changepwdErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new ChangePwd($formData, new Connection(include DB_CONFIG), $this->session['user']);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        if($this->mainLogicObject->handler($this->session['token'],
            array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include CHANGE_PASSWORD_ASSIGNMENTS))
        {
            unset($this->session['token']);

            $this->redirectToHome();   // TODO - go to info page "password changed successfull" ???
        }else
            header("Location: ./changePassword.php");
    }
}