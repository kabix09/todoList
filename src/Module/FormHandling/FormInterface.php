<?php
namespace App\Module\FormHandling;

interface FormInterface
{
    public function checkToken(?string $serverToken = NULL): bool;
    public function validData(array $filter, array $assignments): bool;
    public function handler(?string $serverToken = NULL, array $filter, array $assignments): bool;
}