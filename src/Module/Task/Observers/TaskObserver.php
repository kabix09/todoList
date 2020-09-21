<?php
namespace App\Module\Task\Observers;

use App\Module\Observer\Observer;
use App\Module\Task\Create;

abstract class TaskObserver implements Observer
{
    protected $createTask;
    public function __construct(Create $createTask)
    {
        $this->createTask = $createTask;
        $createTask->attach($this);
    }

    abstract public function doUpdate(Create $createTask);
}