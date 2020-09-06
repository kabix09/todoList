<?php

use App\Filter\CallbackAbstract;
use App\Filter\Elements\ {Result, Messages};

return $config = [
    'filters' => [
        'trim' => new class () extends CallbackAbstract{

            public function __invoke($item, $params): Result
            {
                $this->filteredValue = trim($item);
                if($this->filteredValue !== $item) {
                    $flag = FALSE;
                    $this->message = Messages::getMessage('trim');
                }
                return $this->createResult($flag ?? TRUE);
            }
        },
        'strip_tags' => new class () extends CallbackAbstract {

            public function __invoke($item, $params): Result
            {
                $this->filteredValue = strip_tags($item);
                if($this->filteredValue !== $item) {
                    $flag  = FALSE;
                    $this->message = Messages::getMessage('strip_tags');
                }

                return $this->createResult($flag ?? TRUE);
            }
        },
        'length' => new class () extends CallbackAbstract {

            public function __invoke($item, $params): Result
            {
                if(isset($parms['min']) && (strlen($item) < $parms['min']))
                {
                    $flag = FALSE;
                    $this->filteredValue = substr($item, 0, $parms['min']);
                    $this->message[] = Messages::$messages['length_too_short'];
                }
                if(isset($parms['max']) && (strlen($item) > $parms['max']))
                {
                    $flag = FALSE;
                    $this->filteredValue = substr($item, 0, $parms['max']);
                    $this->message[] = Messages::$messages['length_too_long'];
                }

                return $this->createResult($flag ?? TRUE);
            }
        }
    ],
    'validators' => [

    ]
];