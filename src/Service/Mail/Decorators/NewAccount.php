<?php declare(strict_types=1);
namespace App\Service\Mail\Decorators;

use App\Entity\User;
use App\Service\Mail\Mail;
use App\Service\Mail\MailFactory;
use PHPMailer\PHPMailer\PHPMailer;

class NewAccount extends Mail
{
    private const MAIL_TITLE = "New Account verification";
    private const MAIL_BUTTON = "Click here";

    private const MESSAGE_ASSEMBLE = "Thanks for using our service :) \r\n To activate account please %s";

    private const HREF_TAG = '<a href="%s">%s</a>';
    private const AUTH_LINK= '%s://%s:%s/public/scripts/user/activateAccount.php?email=%s&nick=%s&key=%s';

    private Mail $mailFactory;
    private User $userInstance;

    public function __construct(MailFactory $mailFactory)
    {
        $this->mailFactory = $mailFactory;
    }

    public function create(User $userInstance, array $config): PHPMailer
    {
        $this->userInstance = $userInstance;

        $this->mailFactory->setTitle(self::MAIL_TITLE);
        $this->mailFactory->setContent(
            $this->getBody()
        );

        return $this->mailFactory->create($userInstance, $config);
    }

    private function getBody(): string
    {
        return sprintf(self::MESSAGE_ASSEMBLE, $this->generateHref());
    }

    private function generateHref(string $buttonValue = ""): string
    {
        return sprintf(self::HREF_TAG,
            $this->generateAuthLink(),
            empty($buttonValue) ? self::MAIL_BUTTON : $buttonValue
        );
    }

    private function generateAuthLink(): string
    {
        return sprintf(self::AUTH_LINK,
            $_SERVER['REQUEST_SCHEME'],
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_PORT'],
            $this->userInstance->getEmail(),
            $this->userInstance->getNick(),
            $this->userInstance->getKey()
        );
    }


}