<?php
namespace App\Module\FormHandling\User\Register\Observers;

use App\Module\FormHandling\User\Register\Observers\RegisterObserver;
use App\Service\Logger\Logger;
use App\Service\Logger\MessageSheme;
use App\Module\Observer\Observable;
use App\Module\FormHandling\User\Register\Register;
use App\Entity\User;
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

    public function update(Observable $observable)
    {
        if($observable->getProcessStatus() === "correct")
        {
            $user = $observable->getObject();

            try{
                $mail = $this->buildMail($user->getEmail(),
                                    "New Account verification",
                                        include(ROOT_PATH . "./templates/Mails/newAccount.php"),
                                        require_once ROOT_PATH . './config/smtp.config.php');

                if($mail->send())
                {
                    $config = new MessageSheme($observable->getObject()->getNick(), __CLASS__, __FUNCTION__, TRUE);
                    $this->logger->error("activation email sended successfully", [$config]);
                }else
                {
                    throw new \RuntimeException("system error - couldn't send activation mail");
                }
            }catch (\Exception $e)
            {
                $config = new MessageSheme($observable->getObject()->getNick(), __CLASS__, __FUNCTION__, TRUE);
                $this->logger->info($e->getMessage(), [$config]);

                die("mail system error - the email could not be sent :<");  // ToDo - redirect to error page
            }
        }
    }

    public function doUpdate(Register $register)
    {
        if($register === $this->register)
            $this->doUpdate($register);
    }

    private function buildMail(string $recipientEmail, string $title, string $content, array $config): PHPMailer {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = $config['smtp'];
        $mail->Port = $config['port'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = TRUE;

        $mail->Username = $config['username'];
        $mail->Password = $config['password'];

        $mail->setFrom($config['from'][0], $config['from'][1]);    // eg company address
        $mail->addAddress($recipientEmail);
        $mail->addReplyTo($config['replyTo'][0], $config['replyTo'][1]);      // eg support address

        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $content;

        return $mail;
    }
}