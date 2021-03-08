<?php declare(strict_types=1);
namespace App\Module\FormActions\Task;

use App\Module\FormActions\BaseFormActions;
use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Entity\Task;
use App\Module\Observer\Generic\ErrorObserver;
use App\Module\FormHandling\Task\Send;
use App\Module\Observer\Generic\SessionObserver;
use App\Service\Session\Session;

class SendTask extends BaseFormActions
{
    private Task $task;

    protected function clearErrors(): void
    {
        if (isset($this->session['sendErrors']))
            unset($this->session['sendErrors']);
    }

    protected function setupObserverLogic(array $formData, Connection $connection): void
    {
        $this->mainLogicObject = new Send($formData, $connection, $this->task);

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
            Config::init()::action(Constants::TASK)::module(Constants::ASSIGNMENTS)::get()
        )) {
            unset($this->session['token']);

            // 2 - don't touch task list, only index file manage to download and handle it
            // index.php refresh automatically task list in purpose to always handle lasted version

            $this->redirectToHome();
        }else
            header("Location: ./send.php?id={$this->task->getId()}&owner={$this->task->getOwner()}");

    }

    // -------------------------------------------
    public function setTask(Task $task): void
    {
        $this->task = $task;
    }
}