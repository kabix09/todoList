<?php
namespace App\Module\Access\TaskActions;

use App\Module\Access\BaseTaskAccess;
use App\Service\Logger\MessageSheme;

final class Remove extends BaseTaskAccess
{
    protected function main(array $queryParams): void
    {
        if($this->taskRepository->remove([
                "WHERE" =>NULL,
                "AND" => ["id = '{$queryParams[self::QUERY_PARAMETERS[self::ID]]}'", "owner = '{$queryParams[self::QUERY_PARAMETERS[self::OWNER]]}'"]
            ]))
        {
            // log event
            $config = new MessageSheme($this->session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully removed task with id: {$queryParams[self::QUERY_PARAMETERS[self::ID]]}", [$config]);
            // no need to remove from session because
            // index.php automatically refresh task list
            $this->redirectToHome();
        }else{
            throw new \RuntimeException("An attempt to remove task with id: {$queryParams[self::QUERY_PARAMETERS[self::ID]]} has failed");
        }
    }
}