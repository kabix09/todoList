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

    protected function fixDate()
    {
        $this->data['start_date'] = $this->mergeDataField($this->data['start_date'], $this->data['start_time']);
        unset($this->data['start_time']);
        $this->data['target_end_date'] = $this->mergeDataField($this->data['target_end_date'], $this->data['end_time']);
        unset($this->data['end_time']);
    }

    protected function mergeDataField(string $data, string $time): string {
        if(empty($data) || empty($time))
            return "0000-00-00 00:00:00";   // zero time in using time format

        return $data . " " . $time . ":00";
    }
}