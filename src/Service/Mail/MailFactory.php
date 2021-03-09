<?php declare(strict_types=1);
namespace App\Service\Mail;

use App\Entity\User;
use App\Service\Logger\{Logger, MessageSheme};
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

final class MailFactory extends Mail
{
    private PHPMailer $mail;
    private Logger $logger;
    private MailParamsValidator $mailParamsValidator;

    private string $mailTitle;
    private string $mailContent;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->mailParamsValidator = new MailParamsValidator();
    }

    public function create(User $userInstance, array $config): PHPMailer
    {
        try {
            // check mail config parameters
            $smtp = $this->mailParamsValidator->validate($config);

            // create mail instance
            $this->mail = new PHPMailer(true);

            $this->mail->isSMTP();
            $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
            $this->mail->Host = $smtp['smtp'];
            $this->mail->Port = $smtp['port'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->SMTPAuth = TRUE;

            $this->mail->Username = $smtp['username'];
            $this->mail->Password = $smtp['password'];

            $this->mail->setFrom($smtp['from'][0], $smtp['from'][1]);    // eg company address
            $this->mail->addAddress($userInstance->getEmail());
            $this->mail->addReplyTo($smtp['replyTo'][0], $smtp['replyTo'][1]);      // eg support address

            $this->mail->isHTML(true);

            $this->mail->Subject = $this->mailTitle;
            $this->mail->Body = $this->mailContent;

        }catch (\Exception $e)
        {
            $config = new MessageSheme($userInstance->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->error("PHPMailer instance creation fail. Message: {$e->getMessage()}", [$config]);
        }

        return $this->mail;
    }

    public function setTitle(string $mailTitle): void
    {
        $this->mailTitle = $mailTitle;
    }

    public function setContent(string $mailContent): void
    {
        $this->mailContent = $mailContent;
    }

}