<?php
namespace App\Module\FormActions\Task;

use App\Module\FormActions\BaseFormActions;
use App\Service\Config\{Config, Constants};
use App\Module\Access\QueryParameters;
use ConnectionFactory\Connection;
use App\Module\Observer\Generic\ErrorObserver;
use App\Module\FormHandling\Task\Edit;
use App\Module\Observer\Generic\SessionObserver;

final class EditTask extends BaseFormActions
{
    private int $taskID;

    protected function clearErrors(): void
    {
        if (isset($this->session['editErrors']))
            unset($this->session['editErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new Edit($formData, $connection, $this->taskID);

        // create usefully observers
        new ErrorObserver($this->mainLogicObject);
        new SessionObserver($this->mainLogicObject);
    }

    protected function main(array $queryParams): void
    {
        // no need use exit() method after header() because it is declared in parent core() function

        if($this->mainLogicObject->handler(
            $this->session['token'],
            array_merge(
                Config::init()::module(Constants::FILTER_VALIDATE)::get(),
                Config::init()::module(Constants::FILTER_SANITIZE)::get()
            ),
            Config::init()::action(Constants::TASK)::module(Constants::ASSIGNMENTS)::get()
        )) {
            unset($this->session['token']);

            // 2 - don't touch task list, only index file manage to download and handle it
            // index.php refresh automatically task list in purpose to always handle lasted version

            $this->redirectToHome();
        }else {
            header("Location: ./edit.php?id={$this->taskID}&owner={$queryParams[\App\Module\Access\TaskActions\Edit::QUERY_PARAMETERS[\App\Module\Access\TaskActions\Edit::OWNER]]}");
            die();
        }
    }

    // -------------------------------------------
    public function setTaskID(int $id): void
    {
        $this->taskID = $id;
    }
}