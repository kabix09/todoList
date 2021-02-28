<?php
namespace App\Module\Form\Login\Observers;

use ConnectionFactory\Connection;
use App\Entity\User;
use App\Service\Manager\UserManager;
use App\Module\Form\Login\Login;
use App\Module\Observer\Observable;
use App\Repository\UserRepository;

final class DateObserver extends LoginObserver
{

    public function doUpdate(Login $login) : void
    {
        if($login->getProcessStatus() === "correct")
        {
            try {
                $this->updateLastLoginDate(new UserManager(
                                                $login->getObject(),
                                                new UserRepository(
                                                    new Connection(include DB_CONFIG))));
            } catch (\RuntimeException $e) {
                var_dump($e->getMessage());
                die();
            }
        }
    }

    public function update(Observable $observable)
    {
        if($observable === $this->login)
            $this->doUpdate($observable);
    }

    private function updateLastLoginDate(UserManager $userManager){

        if($userManager->upgradeLastLogin())
            return TRUE;
        else
            throw new \RuntimeException("system error - couldn't update last login date");
    }
}