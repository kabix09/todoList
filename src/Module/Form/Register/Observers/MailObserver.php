<?php
namespace App\Module\Form\Register\Observers;

use App\Logger\Logger;
use App\Logger\MessageSheme;
use App\Mailer\Mail;
use App\Module\Observer\Observable;
use App\Module\Form\Register\Register;
use App\Entity\User;

class MailObserver extends RegisterObserver
{
    private Logger $logger;

    public function __construct(Register $register)
    {
        $this->logger = new Logger();

        parent::__construct($register);
    }

    public function update(Observable $observable)
    {
        if($observable->getProcessStatus() === "correct")
        {
            $user = $observable->getObject();

            $mail = $this->buildMail($user->getEmail(), "New Account verification", include(ROOT_PATH . "./templates/Mails/newAccount.php"));

            $mail->setHeaders(TRUE);

            try{
                if(!$mail->send())
                {
                    throw new \RuntimeException("system error - couldn't send activation mail");
                }
            }catch (\Exception $e)
            {
                $config = new MessageSheme($observable->getObject()->getNick(), __CLASS__, __FUNCTION__);
                $this->logger->error($e->getMessage(), [$config]);

                die();
            }
        }
    }

    public function doUpdate(Register $register)
    {
        if($register === $this->register)
            $this->doUpdate($register);
    }

    private function buildMail(string $nick, string $title, string $content): Mail{
        return new Mail(
                    $nick,
                    $title,
                    $content
                );
    }
}