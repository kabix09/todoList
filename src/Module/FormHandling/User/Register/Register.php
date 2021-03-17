<?php
namespace App\Module\FormHandling\User\Register;

use App\Service\EntityManager\User\Builder\UserBuilder;
use ConnectionFactory\Connection;
use App\Entity\User;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\User\UserManager;
use App\Module\FormHandling\User\PasswordForm;

final class Register extends PasswordForm
{
    protected const LOGIN_ERROR = "this login already exists";

    public function __construct(array $formData, Connection $connection)
    {
        parent::__construct($formData, $connection);
    }

    protected function doHandler()
    {
        if ($this->checkNick())
        {
            $this->errors['nick'][] = self::LOGIN_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        } elseif (!$this->checkPassword()){
            $this->errors['repeatPassword'][] = self::PASSWORD_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if ($this->processStatus === NULL)
        {
            unset($this->data['repeatPassword']);
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

                // prepare object
            $this->object = $this->prepareUserObject();

                // insert new user
            if(!$this->repository->insert($this->object))
            {
                throw new \RuntimeException("couldn't create new user :/");
            }else{
                $config = new MessageSheme($this->object->getNick(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->info("Successfully registered user: \"{$this->object->getNick()}\"", [$config]);
            }

                // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }else{
            $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
            $this->logger->error("The attempt to register has failed", [$config]);
        }
    }


    private function prepareUserObject() : User
    {
        $userManager = new UserManager(
            new UserBuilder($this->data),
            $this->repository
        );
            // hash password
        $userManager->changePassword($this->data['password']);
        $userManager->generateKey();

        return $userManager->return();
    }
}