<?php
namespace App\Module\FormActions\User;

use App\Module\FormActions\BaseFormActions;
use ConnectionFactory\Connection;
use App\Module\Observer\Generic\ErrorObserver;
use App\Module\Form\User\ChangePassword\ChangePass;
use App\Module\Observer\Generic\SessionObserver;

class ChangePassword extends BaseFormActions
{
    protected function clearErrors(): void
    {
        if(isset($session['changepwdErrors']))
            unset($session['changepwdErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new ChangePass($formData, new Connection(include DB_CONFIG), $this->session['user']);

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