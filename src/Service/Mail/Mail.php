<?php declare(strict_types=1);
namespace App\Service\Mail;

use App\Entity\User;
use PHPMailer\PHPMailer\PHPMailer;

abstract class Mail
{
    abstract public function create(User $objectInstance, array $config) : PHPMailer;
}
