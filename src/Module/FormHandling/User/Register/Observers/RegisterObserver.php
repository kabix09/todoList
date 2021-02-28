<?php
namespace App\Module\FormHandling\User\Register\Observers;

use App\Module\Observer\Observer;
use App\Module\FormHandling\User\Register\Register;

abstract class RegisterObserver implements Observer
{
    protected $register;

    /**
     * RegisterObserver constructor.
     * @param Register $register
     */
    public function __construct(Register $register)
    {
        $this->register = $register;
        $register->attach($this);
    }

    abstract public function doUpdate(Register $register);
}