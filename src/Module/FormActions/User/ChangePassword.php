<?php
namespace App\Module\FormActions\User;

use App\Module\FormActions\BaseFormActions;
use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Module\Observer\Generic\ErrorObserver;
use App\Module\FormHandling\User\ChangePassword\ChangePass;
use App\Module\Observer\Generic\SessionObserver;

class ChangePassword extends BaseFormActions
{
    protected function clearErrors(): void
    {
        if(isset($session['changepassErrors']))
            unset($session['changepassErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new ChangePass($formData, $connection, $this->session['user']);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        if($this->mainLogicObject->handler(
            $this->session['token'],
            array_merge(
                Config::init()::module(Constants::FILTER_VALIDATE)::get(),
                Config::init()::module(Constants::FILTER_SANITIZE)::get()
            ),
            Config::init()::action(Constants::CHANGE_PASSWORD)::module(Constants::ASSIGNMENTS)::get()
        )) {
            unset($this->session['token']);

            $this->redirectToHome();   // TODO - go to info page "password changed successfull" ???
        }else
            header("Location: ./changePassword.php");
    }
}