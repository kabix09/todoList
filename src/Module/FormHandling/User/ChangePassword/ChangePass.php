<?php
namespace App\Module\FormHandling\User\ChangePassword;

use App\Service\EntityManager\User\Builder\UserBuilder;
use ConnectionFactory\Connection;
use App\Entity\User;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\User\UserManager;
use App\Module\FormHandling\User\PasswordForm;

final class ChangePass extends PasswordForm
{
    public function __construct(array $formData, Connection $connection, User $user)
    {
        parent::__construct($formData, $connection);
        $this->object = $user;
    }

    protected function doHandler()
    {
        if (!$this->checkPassword())
        {
            $this->errors['repeatPassword'][] = self::PASSWORD_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if($this->processStatus == NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            if ($this->processStatus === self::PROCESS_STATUS[2]) {

                $userManager = new UserManager(
                    new UserBuilder($this->object),
                    $this->repository
                );

                try{
                    if(!$userManager->changePassword($this->data['password'])) {

                        throw new \RuntimeException("password couldn't be changed");
                    }

                    $config = new MessageSheme($this->object->getNick(), __CLASS__, __FUNCTION__, TRUE);
                    $this->logger->info("Successfully changed password", [$config]);

                    // update user handled in session
                    $this->object = $userManager->return();
                }catch (\Exception $e)
                {
                    $config = new MessageSheme($this->object->getNick(), __CLASS__, __FUNCTION__, TRUE);
                    $this->logger->error($e->getMessage(), [$config]);
                }

                // change status
                $this->processStatus = self::PROCESS_STATUS[1];
            }
        }else{
            $config = new MessageSheme($this->object->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->error("An attempt to change password on \"{$this->object->getNick()}\" account has failed", [$config]);
        }
    }

}