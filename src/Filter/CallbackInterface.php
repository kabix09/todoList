<?php
namespace App\Filter;

use App\Filter\Elements\Result;

interface CallbackInterface
{
    public function __invoke($item, $params) : Result;
}