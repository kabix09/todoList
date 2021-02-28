<?php
namespace App\Service\Filter;
use App\Service\Filter\Elements\Result;

final class Filter extends AbstractFilter
{
    public function process(array $data){
        if(!(isset($this->assignments) && is_array($this->assignments)))
            return NULL;

        foreach ($data as $key => $value)   // key is associated input name
            $this->results[$key] = new Result($value, array());

        $toDo = $this->assignments;
        if(isset($toDo['*'])){
            $this->processGlobalAssignment($toDo['*'], $data);
            unset($toDo['*']);
        }

        foreach ($toDo as $key => $assignment) {
            $this->processAssignment($assignment, $key);
        }
    }

    public function processGlobalAssignment($assignments, array $data) : void {
        foreach ($assignments as $task) {
            if ($task === NULL) continue;

            foreach ($data as $key => $value){
                $result = $this->callbacks[$task['key']]($this->results[$key]->getItem(), $task['params']);

                $this->results[$key]->mergeResults($result);
            }
        }
    }

    public function processAssignment($assignment, string $key) : void {
        foreach ($assignment as $task){
            if($task === NULL) continue;

            $result = $this->callbacks[$task['key']]($this->results[$key]->getItem(), $task['params']);

            $this->results[$key]->mergeResults($result);
        }
    }
}