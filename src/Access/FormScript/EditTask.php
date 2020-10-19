<?php
namespace App\Access\FormScript;

use App\Access\BaseFormScript;
use App\Connection\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Task\Edit;
use App\Module\SessionObserver;

class EditTask extends BaseFormScript
{
    protected function clearErrors(): void
    {
        if (isset($session['editErrors']))
            unset($session['editErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new Edit($formData, $connection);

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

            // 2 - don't touch task list, only index file manage to download and handle it
            // index.php refresh automatically task list in purpose to always handle lasted version

            header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php");
        }else
            header("Location: ./editTask.php?id={$queryParams[self::QUERY_VARIABLES[self::ID]]}&owner={$queryParams[self::QUERY_VARIABLES[self::OWNER]]}");
    }
}