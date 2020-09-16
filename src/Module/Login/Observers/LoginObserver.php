<?php
namespace App\Module\Login\Observers;

use App\Module\Observer\Observer;
use App\Module\Login\Login;

abstract class LoginObserver implements Observer
{
    protected $login;
    public function __construct(Login $login)
    {
        $this->login = $login;
        $login->attach($this);
    }

    abstract public function doUpdate(Login $login);
}