<?php
namespace App\Manager;
use App\Connection\Connection;
use App\Entity\Mapper\TaskMapper;
use App\Entity\Mapper\UserMapper;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Token\Token;

final class UserManager extends BaseManager
{
    public function __construct($data, UserRepository $userRepository)
    {
        $this->setObject($data);

        parent::__construct($userRepository);
    }

    protected function setObject($data)
    {
        if(is_array($data))
            $this->object = UserMapper::arrayToEntity($data);
        elseif($data instanceof User)
            $this->object = $data;
        else
            $this->object = new User();
    }

    public function return(): User
    {
        return $this->object;
    }

    public function update(): bool
    {
        return $this->doUpdate([
            "where" => NULL,
            "AND" => ["nick = '{$this->object->getNick()}'", "email = '{$this->object->getEmail()}'"]
        ]);
    }

    // ---- base entity config functions ----
    public function changePassword(string $newPassword): void
    {
        $newHashedPassword = $this->hashPassword($newPassword);
        if($newHashedPassword !== FALSE && !is_null($newHashedPassword)) {
            $this->object->setPassword($newHashedPassword);
        }else
            throw new \Exception("try password hash failed");
    }
    public function setLastLogin(): void
    {
        $this->object->setLastLoginDate(
            $this->getDate()
        );
    }
    public function setCreateAccount(): void
    {
        $this->object->setCreateAccountDate(
            $this->getDate()
        );
    }
    public function setDefaultStatus(): void
    {
        $this->object->setStatus(
            "inactive"
        );
    }

    public function generateKey(){
        $this->object->setKey(
            (new Token())->generate(50)->hash()->binToHex()->getToken()
        );
    }


    public function getUserTasks(TaskRepository $taskRepository): void
    {
        $rawList = iterator_to_array(
            $taskRepository->fetchByOwner(
                $this->object->getNick()
            ), TRUE);

        foreach ($rawList as $key => $object){
            $rawList[$key] = TaskMapper::entityToArray($object);
        }

        $this->object->setTaskCollection($rawList);
    }

    // ---- action functions
    public function activateTheAccount(): bool{
        $this->object->setStatus('active');

        return $this->update();
    }

    public function blockTheAccount(): bool{
        $this->object->setStatus('blocked');

        return $this->update();
    }

    public function upgradeLastLogin(): bool{
        $this->setLastLogin();

        return $this->update();
    }

    public function banUser(int $days = 3): bool{
        $this->object->setEndBan(
            $this->getDate(strtotime("+{$days} days"))
        );

        return $this->update();
    }

    public function changeAccountKey(?string $newKey = NULL)
    {
        $this->object->setKey($newKey);

        return $this->update();
    }
        // finish ban and activate account
    public function manageBanStatus(User $user, string $time): ?bool{
        $banDate = $this->object->getEndBan();

        if(isset($banDate) && ($banDate < $this->getDate()))
        {
            $this->object->setEndBan();     // change date
            return $this->activateTheAccount();    // change status to active
        }
        return NULL;
    }

    // ---- ---- support functions
    public function hashPassword(string $password): ?string{
        return
            password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
    }
}