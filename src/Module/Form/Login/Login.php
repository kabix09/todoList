<?php
namespace App\Module\Form\Login;

use App\Module\Form\UserForm;

final class Login extends UserForm
{
    protected const LOGIN_ERROR = "incorrect login";
    protected const PASSWORD_ERROR = "incorrect password";

    protected function doHandler()
    {
        if (!$this->checkNick())
        {
            $this->errors['nick'][] = self::LOGIN_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        } elseif (!$this->checkPassword())
        {
            $this->errors['password'][] = self::PASSWORD_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if ($this->processStatus === NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            $this->processStatus = self::PROCESS_STATUS[1];

            $this->logger->info("Successfully logged in", [
                "personalLog" => TRUE,
                "userFingerprint" => $this->object->getNick(),
                "className" => __CLASS__,
                "functionName" => __FUNCTION__
            ]);
        }else{
            $this->logger->error("An attempt to log into the \"{$this->data['nick']}\" account has failed", [
                    "userFingerprint" => $_SERVER['REMOTE_ADDR'],
                    "className" => __CLASS__,
                    "functionName" => __FUNCTION__
                ]);
        }
    }

    protected function checkPassword(): bool
    {
        if(!password_verify(
                $this->data['password'],
                $this->object->getPassword()
            )){
            return FALSE;
        }
        return TRUE;
    }
}