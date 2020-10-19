<?php
namespace App\Access\FormScript;

use App\Access\BaseFormScript;
use App\Connection\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Task\Create;
use App\Module\SessionObserver;

final class CreateTask extends BaseFormScript
{

    protected function clearErrors(): void
    {
        if (isset($this->session['createErrors']))
            unset($this->session['createErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new Create($formData, $connection, $this->session['user']);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        // no need use exit() method after header() because it is declared in parent core() function

        if($this->mainLogicObject->handler($this->session['token'],
            array_merge(include FILTER_VALIDATE, include FILTER_SANITIZE), include TASK_ASSIGNMENTS))
        {
            unset($this->session['token']);

            // don't touch task list, only index file manage to download and handle it
            // index.php refresh automatically task list in purpose to always handle lasted version

            header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
        }else
            header("Location: ./create.php");
    }
}