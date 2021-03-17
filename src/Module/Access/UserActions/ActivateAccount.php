<?php
namespace App\Module\Access\UserActions;

use App\Module\Access\BaseUserAccess;
use App\Service\EntityManager\User\Builder\UserBuilder;
use ConnectionFactory\Connection;
use App\Entity\User;
use App\Service\Logger\MessageSheme;
use App\Service\EntityManager\User\UserManager;
use App\Service\Session\Session;

final class ActivateAccount extends BaseUserAccess
{
    private UserManager $userManager;

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

        $this->userManager = new UserManager(
            new UserBuilder($this->user),
            $this->userRepository
        );

        if($this->userManager->activateTheAccount())
        {
            // remove old key
            $this->userManager->changeAccountKey();

            // fetch updated user
            $this->user = $this->userManager->return();

            // log event
            $config = new MessageSheme($this->user->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully activated account", [$config]);

            // update user handled in session
            $this->session['user'] = $this->user;

            // redirect to confirm page
            include_once SITE_ROOT . "./templates/mails/verificationSuccess.php";
            die();
        }
        else
            throw new \RuntimeException("The account with id: {$this->user->getId()} couldn't be activated");
    }
}