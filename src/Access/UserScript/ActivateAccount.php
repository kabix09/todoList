<?php
namespace App\Access\UserScript;

use App\Access\BaseUserAccess;
use ConnectionFactory\Connection;
use App\Entity\User;
use App\Service\Logger\MessageSheme;
use App\Service\Manager\UserManager;
use App\Service\Session\Session;

final class ActivateAccount extends BaseUserAccess
{


    public function __construct(Session $session, Connection $connection)
    {
        parent::__construct($session, $connection);

    }

    private function checkUserKey(User $user, string $key): bool
    {
        return
            $user->getKey() === $key;
    }

    protected function main(array $queryParams)
    {
        if(!$this->checkUserKey($this->user, $queryParams[static::QUERY_PARAMETERS[static::KEY]]))
            throw new \Exception('incorrect activation key');

        $new = new UserManager($this->user, $this->userRepository);

        if($new->activateTheAccount())
        {
            // remove old key
            $new->changeAccountKey();

            // log event
            $config = new MessageSheme($this->user->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully activated account", [$config]);

            // save user in session
            $this->session['user'] = $this->user;

            // redirect to confirm page
            include_once ROOT_PATH . "./templates/mails/verificationSuccess.php";
            die();
        }
        else
            throw new \RuntimeException("The account with id: {$this->user->getId()} couldn't be activated");
    }
}