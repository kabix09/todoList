<?php
namespace App\Access\FormScript\User;

use App\Access\BaseFormAccess;
use ConnectionFactory\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Login\Observers\DateObserver;
use App\Module\SessionObserver;

class Login extends BaseFormAccess
{
    protected function clearErrors(): void
    {
        if (isset($this->session['loginErrors']))
            unset($this->session['loginErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new \App\Module\Form\Login\Login($formData, $connection);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
        new DateObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        // no need use exit() method after header() because it is declared in parent core() function

        if($this->mainLogicObject->handler($this->session['token'],
            array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include LOG_ASSIGNMENTS))
        {
            unset($this->session['token']);

            $this->session['user'] = $this->mainLogicObject->getObject();

            if($this->session['user']->getStatus() === 'active')
                $this->redirectToHome();
            else
                header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/templates/user/accountStatus.php");
        }else
            header("Location: ./login.php");
    }
}