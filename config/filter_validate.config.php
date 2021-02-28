<?php

use App\Service\Filter\CallbackAbstract;
use App\Service\Filter\Elements\ {Result, Messages};

return $filterValidate = [
    'trim' => new class () extends CallbackAbstract{

        public function __invoke($item, $params): Result
        {
            $this->resetOldMessage();

            $this->filteredValue = trim($item);
            if($this->filteredValue !== $item) {
                $flag = FALSE;
                $this->message[] = Messages::getMessage('trim');
            }
            return $this->createResult($flag ?? TRUE);
        }
    },
    'strip_tags' => new class () extends CallbackAbstract {

        public function __invoke($item, $params): Result
        {
            $this->resetOldMessage();

            $this->filteredValue = strip_tags($item);
            if($this->filteredValue !== $item) {
                $flag  = FALSE;
                $this->message[] = Messages::getMessage('strip_tags');
            }

            return $this->createResult($flag ?? TRUE);
        }
    },
    'length' => new class () extends CallbackAbstract {

        public function __invoke($item, $params): Result
        {
            $this->resetOldMessage();

            $this->filteredValue = $item;

            if(isset($params['min']) && (strlen($item) < $params['min']))
            {
                $flag = FALSE;
                $this->filteredValue = substr($item, 0, $params['min']);
                $this->message[] = sprintf(Messages::$messages['length_too_short'],
                                            $params['min']);

            }
            if(isset($params['max']) && (strlen($item) > $params['max']))
            {
                $flag = FALSE;
                $this->filteredValue = substr($item, 0, $params['max']);
                $this->message[] = sprintf(Messages::$messages['length_too_long'],
                                            $params['max']);
            }

            return $this->createResult($flag ?? TRUE);
        }
    }
];