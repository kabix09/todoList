<?php

use App\Filter\CallbackAbstract;
use App\Filter\Elements\ {Result, Messages};

return $filterSanitize = [
    'htmlentities' => new class () extends CallbackAbstract {

        public function __invoke($item, $params): Result
        {
            $this->resetOldMessage();

            $this->filteredValue = htmlentities($item);
            if($this->filteredValue !== $item)
            {
                $flag = FALSE;
                $this->message[] = Messages::getMessage('htmlentities');
            }

            return $this->createResult($flag ?? TRUE);
        }
    }
];