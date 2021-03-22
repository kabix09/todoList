<?php
namespace App\Service\EntityManager\User;

use App\Service\EntityManager\User\Builder\UserBuilder;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\EntityManager\BaseManager;
use App\Service\Token\Token;

final class UserManager extends BaseManager
{
    private ?TaskRepository $taskRepository;

    public function __construct(UserBuilder $userBuilder, UserRepository $userRepository, ?TaskRepository $taskRepository = NULL)
    {
        parent::__construct($userBuilder, $userRepository);

        $this->taskRepository = $taskRepository;
    }

    public function update(array $criteria=[]): bool
    {
        // TODO - update object from UserBuilder not from local variable

        return parent::update([
            "where" => NULL,
            "AND" => ["nick = '{$this->object->getNick()}'", "email = '{$this->object->getEmail()}'"]
        ]);
    }

    public function return(): User
    {
        return $this->objectBuilder->getInstance();
    }

    // TODO - one function: edit - to settup all property at once

    // modify object functions
    public function generateKey(): void
    {
        $this->objectBuilder->setKey(
            (new Token())->generate(50)->hash()->binToHex()->getToken()
        );
    }

    public function setDefaultStatus(): void
    {
        $this->objectBuilder->setStatus(
            "inactive"
        );
    }

    //-----------------------------------------------------------------------------------

    // Lazy loading ???
    public function loadUserTasks(string $userNick): void
    {
        if(is_null($this->taskRepository))
            throw new \Exception("Need to declare TaskRepository instance in " . __CLASS__ . " constructor");

        $rawList = [];
        foreach ($this->taskRepository->fetchByOwner($userNick) as $key => $object){
            $rawList[$key] = $object;
        }

        //$this->object->setTaskCollection($rawList);
        $this->objectBuilder->setTaskList($rawList);
    }

    // ---- account modify functions ----
    public function changePassword(string $newPassword) : bool
    {
        $newHashedPassword = $this->hashPassword($newPassword);

        if($newHashedPassword !== FALSE && !is_null($newHashedPassword)) {

            $this->objectBuilder->changePassword($newHashedPassword);
        }else {
            throw new \Exception("Password hash attempt failed :/");
        }

        return $this->update();
    }

    public function changeAccountKey(?string $newKey = NULL)
    {
        $this->objectBuilder->setKey($newKey);

        return $this->update();
    }

    public function updateLastLogin(): bool{
        $this->objectBuilder->setLastLoginDate(
            $this->getDate()
        );

        return $this->update();
    }

    public function activateTheAccount(): bool{
        $this->objectBuilder->setAccountStatus('active');

        return $this->update();
    }

    public function blockTheAccount(): bool{
        $this->objectBuilder->setAccountStatus('blocked');

        return $this->update();
    }

    // TODO - fix or repair
    public function banUser(int $days = 3): bool{
        $this->objectBuilder->setEndBan(
            $this->getDate(strtotime("+{$days} days"))
        );

        return $this->update();
    }
        // finish ban and activate account
    public function manageBanStatus(User $user, string $time): ?bool{
        $banDate = $this->object->getEndBan();

        if(isset($banDate) && ($banDate < $this->getDate()))
        {
            $this->objectBuilder->setEndBan();     // change date
            return $this->activateTheAccount();    // change status to active
        }
        return NULL;
    }
    // TODO - end

    private function hashPassword(string $password): ?string{
        return
            password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
    }
}