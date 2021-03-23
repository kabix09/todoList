<?php declare(strict_types=1);

namespace App\Service\Session;

use App\Service\EntityManager\Session\Builder\SessionBuilder;
use App\Service\Logger\MessageSheme;
use App\Service\Config\{Config, Constants};
use App\Service\Logger\Logger;
use ConnectionFactory\Connection;
use App\Repository\SessionRepository;
use App\Service\EntityManager\Session\SessionManager;
use App\Service\Session\Counter\Counter;
use App\Entity\Session as SessionEntity;

class Session extends SessionArray
{
    private const PATH_PATTERN = "%s" . DIRECTORY_SEPARATOR . "sess_%s";
    private const DEFAULT_REQUESTS_COUNT = 5;

    private SessionRepository $repository;
    private SessionManager $manager;
    protected SessionEntity $sessionEntity;

    private Logger $logger;

    public function __construct(int $sessionRequestsAmount = 0)
    {
        $this->logger = new Logger();

        $this->sessionEntity = new SessionEntity();
        $this->repository = new SessionRepository(new Connection(Config::init()::module(Constants::DATABASE)::get()));
        $this->manager = new SessionModuleManager(new SessionBuilder($this->sessionEntity), $this->repository);

        try {
            $this->init();
        }
        catch (\Exception $e) {
            $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__, FALSE);
            $this->logger->error($e->getMessage(), [$config]);
        }

        Counter::init(
    $sessionRequestsAmount ?: self::DEFAULT_REQUESTS_COUNT,
    isset($_SESSION['counter']) ?: 0
        );
    }

    public function getSessionVerify(SessionSecurity $sessionSecurity): void
    {
        $sessionSecurity->setSessionVerify(new SessionVerify($this->sessionEntity));
    }
    public function getSessionManager(SessionSecurity $sessionSecurity): void
    {
        $sessionSecurity->setSessionManager($this->manager);
    }

    private function init(): void
    {
            // 1 clear db from old sessions
        if(!$this->garbageCollection()) {
            throw new \RuntimeException("Old sessions could not be deleted from the database");
        }

            // 2 if exists fetch session object from database
        $capturedSessionInstance = $this->manager->fetchSession();

        if(is_null($capturedSessionInstance))                           // 3 if not exist, push new session object into db
        {
            $this->manager->prepareInstance();

            $this->repository->insert($this->manager->return());
        }else {
            $this->manager->updateInstance($capturedSessionInstance);   // 3 if exist, update local object representative
        }

            // 4 start session, if exists in db then, with downloaded key else with new generated key
        if($this->create($this->sessionEntity->getSessionKey()))
        {
            /*
             * 5
             * if it is a new session (e.g. session which weren't initialize in database because its new client)
             * update session instance in database (key & session time)
             */
            if(session_id() !== $this->sessionEntity->getSessionKey())
            {
                $this->manager->updateSessionKey(session_id());
                $this->manager->updateCreateTime();

                if(! $this->manager->refreshSessionEntity()) {
                    throw new \RuntimeException("Couldn't update session instance in database");
                }

                // 6 catch correct session instance from db
                $this->manager->updateInstance(
                    $this->repository->find(array(),
                        [
                            "WHERE" => NULL,
                            "AND" => ["user_ip = '{$_SERVER["REMOTE_ADDR"]}'", "browser_data = '{$_SERVER["HTTP_USER_AGENT"]}'"]
                        ])->current()
                );
            }
        }
    }

    private function create(string $sessionKey): bool
    {
        if(session_id() === '') {
            if(!empty($sessionKey)) {
                session_id($sessionKey);    // must be set before the function will be called
            }

            session_start();

            /*
             * session_start automatically generate ID and save this in cookie PHPSESSID, but
             * in purpose to overwrite time in cookie PHPSESSID with time() - gc.maxlifetime in destroy() func
             */
            setcookie('PHPSESSID', session_id(), time() + ini_get('session.gc_maxlifetime'), "/");   // TODO - delegate create cookie task to other class?

        }else {
            throw new \RuntimeException("Session already exists - current session id: " . session_id());
        }

        return true;
    }

    public function destroy(): void
    {
        // check is session exists / was started
        if(!isset($_COOKIE['PHPSESSID'])) {
            throw new \RuntimeException("Session couldn't be removed because wasn't started before");
        }

        // remove session fingerprint in database
        $this->repository->remove(
            [
                "WHERE" => NULL,
                "AND" => ["user_nick = '{$_SESSION['user']->getNick()}'", "session_key = '{$_COOKIE['PHPSESSID']}'"]
            ]
        );

        unset($this->sessionEntity);    // remove local handled session object


        session_unset();        // destroys data stored in $_SESSION super global array
        session_destroy();      // destroys the session data stored in the session storage

        /*
         * destroy browser data
         * in session_start(), function don't read ID from cookie and successfully generate new token value
         */
        setcookie('PHPSESSID', "", time() - ini_get('session.gc_maxlifetime'), "/");
    }

    public function regenerateID(bool $removeOldSession = true): void
    {
        session_regenerate_id($removeOldSession);
        // need to manually update cookies sess id value
        setcookie('PHPSESSID', session_id(), time() + ini_get('session.gc_maxlifetime'), "/");

        // fetch new key
        $this->manager->updateSessionKey(session_id());
    }

    private function garbageCollection(): bool
    {
        /*
         * 1 - fetch old sessions key
         * 2 - clear db with old sessions records
         * 3 - remove old session filed from server tmp folder
         * ToDO - delegate remove task to other class and outside que eg. RabbitMQ
         */
        $oldDate = date(SessionEntity::DATE_FORMAT, time() - ini_get("session.gc_maxlifetime"));

        $oldSessionsCollection = $this->repository->find(array(),
            [
                "WHERE" => "session_create_time < '{$oldDate}}'"
            ]
        );

        foreach ($oldSessionsCollection as $oldObject)
        {
            $realSessionPath = $this->generatePathToSessionFile($oldObject->getSessionKey());

            if(unlink($realSessionPath)) {

                $this->logger->info("Successfully deleted file with location: {$realSessionPath}", [$config]);
            } else {

                throw new \RuntimeException("Couldn't remove session file: " . $realSessionPath);
            }

            if($this->repository->remove([
                    "WHERE" => "session_key = '{$oldObject->getSessionKey()}'"
            ])) {

                $this->logger->info("Successfully removed session instance by id: {$oldObject->getSessionKey()}", [$config]);
            } else {

                throw new \RuntimeException("Couldn't remove session object by id: " . $oldObject->getSessionKey());
            }
        }

        return true;
    }

    private function generatePathToSessionFile(string $key) : string
    {
        return sprintf(self::PATH_PATTERN, session_save_path(), $key);
    }
}