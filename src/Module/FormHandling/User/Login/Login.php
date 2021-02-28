<?php
namespace App\Module\FormHandling\User\Login;

use App\Service\Logger\MessageSheme;
use App\Module\FormHandling\User\UserForm;

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


            $config = new MessageSheme($this->object->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully logged in", [$config]);
        }else{
            $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
            $this->logger->error("An attempt to log into the \"{$this->data['nick']}\" account has failed", [$config]);
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