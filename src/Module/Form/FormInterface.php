<?php
namespace App\Module\Form;

interface FormInterface
{
    public function checkToken(?string $serverToken = NULL): bool;
    public function validData(array $filter, array $assignments): bool;
    public function handler(?string $serverToken = NULL, array $filter, array $assignments): bool;
}