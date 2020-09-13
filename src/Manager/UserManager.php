<?php
namespace App\Manager;
use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserManager
{
    private User $user;
    private UserRepository $repository;

    public function __construct($data, UserRepository $userRepository)
    {
        if(is_array($data))
            $this->user = UserFactory::arrayToEntity($data);
        elseif($data instanceof User)
            $this->user = $data;
        else
            $this->user = (new UserFactory())->newUser();

        $this->repository = $userRepository;
    }

    public function return(): User
    {
        return $this->user;
    }

    public function hashPassword(string $password, ?User $user = NULL) : void{
        ($user ?? $this->user)->setPassword(
            password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3])
        );
    }


    public function setLastLogin(?User $user = NULL){
        ($user ?? $this->user)->setLastLoginDate(
            $this->getDate()
        );
    }
    public function setCreateAccount(?User $user = NULL){
        ($user ?? $this->user)->setCreateAccountDate(
            $this->getDate()
        );
    }
    public function setDefaultStatus(?User $user = NULL){
        ($user ?? $this->user)->setStatus(
            "inactive"
        );
    }


    public function changePassword(User $user, string $password)
    {
        $user->setPassword(
            password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3])
        );

        return
            $this->repository->update($user, [
                    "where" => NULL,
                    "AND" => ["nick = '{$user->getNick()}'", "email = '{$user->getEmail()}'"]
                ]);
    }

    public function activateTheAccount(User $user){
        $user->setStatus('active');

        return
            $this->repository->update($user, [
                "where" => NULL,
                "AND" => ["nick = '{$user->getNick()}'", "email = '{$user->getEmail()}'"]
            ]);
    }

    public function blockTheAccount(User $user){
        $user->setStatus('blocked');

        return
            $this->repository->update($user, [
                "where" => NULL,
                "AND" => ["nick = '{$user->getNick()}'", "email = '{$user->getEmail()}'"]
            ]);
    }

    public function upgradeLastLogin(User $user){
        $this->setLastLogin($user);

        return
            $this->repository->update($user, [
                "where" => NULL,
                "AND" => ["nick = '{$user->getNick()}'", "email = '{$user->getEmail()}'"]
            ]);
    }

    // finish ban and activate account
    public function upgradeBan(User $user, string $time){
        $banDate = $user->getEndBan();

        if(isset($banDate) && ($banDate < $this->getDate()))
        {
            $user->setEndBan();
            $this->activateTheAccount($user);
        }
    }

    // set ban time
    public function ban(User $user, int $days = 3){

        $user->setEndBan(
            $this->getDate(strtotime("+{$days} days"))
        );

        return
            $this->repository->update($user, [
                "where" => NULL,
                "AND" => ["nick = '{$user->getNick()}'", "email = '{$user->getEmail()}'"]
            ]);
    }

    private function getDate($date = NULL) : string {
        return
            (new \DateTime())->format(User::DATE_FORMAT);
    }
}