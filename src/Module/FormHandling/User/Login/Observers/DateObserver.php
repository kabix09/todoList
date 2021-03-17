<?php
namespace App\Module\FormHandling\User\Login\Observers;

use App\Entity\Mapper\UserMapper;
use App\Module\FormHandling\User\Login\Observers\LoginObserver;
use App\Service\EntityManager\User\Builder\UserBuilder;
use App\Service\Config\{Config, Constants};
use ConnectionFactory\Connection;
use App\Entity\User;
use App\Service\EntityManager\User\UserManager;
use App\Module\FormHandling\User\Login\Login;
use App\Module\Observer\Observable;
use App\Repository\UserRepository;

final class DateObserver extends LoginObserver
{

    public function doUpdate(Login $login) : void
    {
        if($login->getProcessStatus() === "correct")
        {
            try {
                $this->updateLastLoginDate(
                    new UserManager(
                        new UserBuilder($login->getObject()),
                        new UserRepository(
                            new Connection(Config::init()::module(Constants::DATABASE)::get())
                        )
                    )
                );
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

        if($userManager->updateLastLogin())
            return TRUE;
        else
            throw new \RuntimeException("system error - couldn't update last login date");
    }
}