<?php
namespace App\Module\FormHandling\User\Register\Observers;

use App\Module\FormHandling\User\Register\Observers\RegisterObserver;
use App\Service\Logger\Logger;
use App\Service\Logger\MessageSheme;
use App\Module\Observer\Observable;
use App\Module\FormHandling\User\Register\Register;
use App\Entity\User;
use App\Service\Mail\Decorators\NewAccount;
use App\Service\Mail\MailFactory;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailObserver extends RegisterObserver
{
    private Logger $logger;

    public function __construct(Register $register)
    {
        $this->logger = new Logger();

        parent::__construct($register);
    }

    public function update(Observable $observable): void
    {
        $userInstance = clone $observable->getObject();

        try{
            $mailFactory = new NewAccount(new MailFactory());

            $mail = $mailFactory->create(
                        $userInstance,
                        require(SITE_ROOT . './config/smtp.config.php')
                    );

            if($mail->send())
            {
                $config = new MessageSheme($userInstance->getNick(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->error("Activation email sent successfully", [$config]);
            }else
            {
                throw new \RuntimeException("System error - couldn't send activation mail");
            }
        }catch (\Exception $e)
        {
            $config = new MessageSheme($userInstance->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info($e->getMessage(), [$config]);

            header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/templates/mails/newAccountFailed.php");
        }

    }

    public function doUpdate(Register $register): void
    {
        if($register === $this->register & $register->getProcessStatus() === "correct") {
            $this->doUpdate($register);
        }
    }
}