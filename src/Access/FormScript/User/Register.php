<?php
namespace App\Access\FormScript\User;

use App\Access\BaseFormAccess;
use App\Connection\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Register\Observers\MailObserver;
use App\Module\SessionObserver;

class Register extends BaseFormAccess
{
    protected function clearErrors(): void
    {
        if (isset($this->session['registerErrors']))
            unset($this->session['registerErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new \App\Module\Form\Register\Register($formData, $connection);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
        new MailObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        if($this->mainLogicObject->handler($this->session['token'],
            array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include REG_ASSIGNMENTS))
        {
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