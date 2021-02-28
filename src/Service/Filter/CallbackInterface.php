<?php
namespace App\Service\Filter;

use App\Service\Filter\Elements\Result;

interface CallbackInterface
{
    public function __invoke($item, $params) : Result;
}