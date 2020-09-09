<?php
namespace App\Manager;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserManager
{
    private $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
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
            $this->repository->update($user, ["where" => ["id", "= '{$user->getId()}'"]]);
    }

    public function upgradeLastLogin(User $user){
        $user->setLastLoginDate(
            $this->setDate()
        );

        return
            $this->repository->update($user, ["where" => ["id", "= '{$user->getId()}'"]]);
    }


    private function setDate($date = NULL) : string {
        return
            (new \DateTime($date ?? time()))->format(User::DATE_FORMAT);
    }
}