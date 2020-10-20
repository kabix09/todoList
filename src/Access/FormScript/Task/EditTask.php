<?php
namespace App\Access\FormScript\Task;

use App\Access\BaseFormAccess;
use App\Access\TaskParameters;
use App\Connection\Connection;
use App\Module\ErrorObserver;
use App\Module\Form\Task\Edit;
use App\Module\SessionObserver;

final class EditTask extends BaseFormAccess
{
    protected function clearErrors(): void
    {
        if (isset($this->session['editErrors']))
            unset($this->session['editErrors']);
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

            $this->redirectToHome();
        }else
            header("Location: ./editTask.php?id={$queryParams[TaskParameters::QUERY_VARIABLES[TaskParameters::ID]]}&owner={$queryParams[TaskParameters::QUERY_VARIABLES[TaskParameters::OWNER]]}");
    }
}