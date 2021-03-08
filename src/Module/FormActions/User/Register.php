<?php
namespace App\Module\FormActions\User;

use App\Module\FormActions\BaseFormActions;
use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Module\Observer\Generic\ErrorObserver;
use App\Module\FormHandling\User\Register\Observers\MailObserver;
use App\Module\Observer\Generic\SessionObserver;

class Register extends BaseFormActions
{
    protected function clearErrors(): void
    {
        if (isset($this->session['registerErrors']))
            unset($this->session['registerErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new \App\Module\FormHandling\User\Register\Register($formData, $connection);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
        new MailObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        if($this->mainLogicObject->handler(
            $this->session['token'],
            array_merge(
                Config::init()::module(Constants::FILTER_VALIDATE)::get(),
                Config::init()::module(Constants::FILTER_SANITIZE)::get()
            ),
            Config::init()::action(Constants::REGISTER)::module(Constants::ASSIGNMENTS)::get()
        )) {
            unset($this->session['token']);

            $this->session['login'] = TRUE;
            $this->session['user'] = $this->mainLogicObject->getObject(TRUE);

            // 2 - set header
            if($this->session['user']->getStatus() === 'active')
                $this->redirectToHome();
            else
                include $_SERVER['DOCUMENT_ROOT'] . "/templates/mails/checkEmail.php";
        }else
            header("Location: ./register.php");
    }
}