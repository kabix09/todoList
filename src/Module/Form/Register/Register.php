<?php
namespace App\Module\Form\Register;

use App\Entity\User;
use App\Manager\UserManager;
use App\Module\Form\PasswordForm;

final class Register extends PasswordForm
{
    protected const LOGIN_ERROR = "this login already exists";

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
                $this->logger->info("Successfully registered user: \"{$this->object->getNick()}\"", [
                    "personalLog" => TRUE,
                    "userFingerprint" => $this->object->getNick(),
                    "className" => __CLASS__,
                    "functionName" => __FUNCTION__
                ]);
            }

                // change status
            $this->processStatus = self::PROCESS_STATUS[1];
        }else{
            $this->logger->error("The attempt to register has failed", [
                "userFingerprint" => $_SERVER['REMOTE_ADDR'],
                "className" => __CLASS__,
                "functionName" => __FUNCTION__
            ]);
        }
    }


    private function prepareUserObject() : User
    {
        $userManager = new UserManager($this->data, $this->repository);
            // hash password
        $userManager->hashPassword($this->data['password']);

        return $userManager->return();
    }
}