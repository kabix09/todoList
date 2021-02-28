<?php

use App\Service\Filter\CallbackAbstract;
use App\Service\Filter\Elements\ {Result, Messages};

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
    },
    'email_sanitize' => new class () extends CallbackAbstract{

        public function __invoke($item, $params): Result
        {
            $this->resetOldMessage();

            $this->filteredValue = filter_var($item, FILTER_SANITIZE_EMAIL);

            if($this->filteredValue !== $item)
            {
                $flag = FALSE;
                $this->message[] = Messages::getMessage('email');
            }

            return $this->createResult($flag ?? TRUE);
        }
    }
];