<?php

namespace App\Module\Form;

use App\Connection\Connection;
use App\Entity\Task;
use App\Repository\TaskRepository;

abstract class TaskForm extends FormGeneric
{
    public function __construct(array $formData, Connection $connection)
    {
        parent::__construct($formData,
                            new TaskRepository($connection));
    }

    abstract protected function prepareTask(): Task;
}