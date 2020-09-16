<?php
namespace App\Module\Login\Observers;

use App\Module\Login\Login;
use App\Module\Observer\Observable;

final class ErrorObserver extends LoginObserver
{

    public function doUpdate(Login $login)
    {
        if($login->getProcessStatus() === "errors")
        {
            $_SESSION['logForm'] = $login->getErrors();
        }
    }

    public function update(Observable $observable)
    {
        if($observable === $this->login)
            $this->doUpdate($observable);
    }
}