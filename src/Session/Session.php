<?php
namespace App\Session;
use App\Connection\Connection;
use App\Logger\Logger;
use App\Repository\SessionRepository;
use App\Manager\SessionManager;
use App\Session\Counter\Counter;

class Session extends SessionArray
{
    const DEFAULT_REQUESTS_COUNT = 5;

    public SessionVerify $verify;
    private SessionRepository $repository;
    private SessionManager $manager;
    protected \App\Entity\Session $session;

    public Counter $counter;

    public function __construct(?int $sessionRequestsAmount = NULL)
    {
        $this->logger = new Logger();

        $this->repository = new SessionRepository(new Connection(include DB_CONFIG));

        $this->session = new \App\Entity\Session();
        $this->verify = new SessionVerify($this->session);
        $this->manager = new SessionManager($this->session);

        try {
            $this->init();
        }catch (\Exception $e)
        {
            $this->logger->error($e->getMessage(), [
                "userFingerprint" => $_SERVER['REMOTE_ADDR'],
                "fileName" => $e->getFile(),
                "line" => $e->getLine()
            ]);
        }

        $this->counter = Counter::init(
                            $sessionRequestsAmount ?? self::DEFAULT_REQUESTS_COUNT,
                            $_SESSION['counter'] ?? NULL
                                );
    }

    private function init(): void{
        $this->garbageCollection();     // TODO - add 0 garbage cllection -> remove old sessions in db ???

            // 1 download session object from database
        $tmp = $this->fetchSession();


        if($tmp instanceof \App\Entity\Session)
        {
            $this->manager->set($tmp, \App\Entity\Session::class);

        }elseif(is_null($tmp))      // 2 if not exist, create new session bd object
        {
                // create new session object
            $this->manager->init();


                // insert new session object
            $this->repository->insert($this->session);
        }

            // 3 start session, if exists in db then, with downloaded key
        $key = $this->session->getSessionKey();
        $this->create(
            empty($key) ? NULL : $key
        );
    }

    private function create(?string $sessionKey = NULL): void
    {
        if(session_id() === '') {
            if(!is_null($sessionKey))
                session_id($sessionKey);

            session_start();
                    // session_start automatically generate ID and save this in cookie PHPSESSID, but
                    // in purpose to overwrite time in cookie PHPSESSID with time() - gc.maxlifetime in destroy() func
            setcookie('PHPSESSID', session_id(), time() + ini_get('session.gc_maxlifetime'), "/");   // delegate create cookie task to other class?

                // 4 update session instance in database (key & session time) if that is a new session
                //   a session which weren't initialize in database because its new client
            if(session_id() !== $this->session->getSessionKey())
            {
                $this->manager->updateSessionKey(session_id());
                $this->manager->updateCreateTime();

                if(! $this->refreshSessionEntity())
                    throw new \Exception("couldn't update session instance in database");
            }
        }

            // 5 download correct session instance from
         $this->manager->set(
             $this->repository->find(array(),
                 [
                     "WHERE" => NULL,
                     "AND" => ["user_ip = '{$_SERVER["REMOTE_ADDR"]}'", "browser_data = '{$_SERVER["HTTP_USER_AGENT"]}'"]
                 ])->current(),
             \App\Entity\Session::class);
    }

    // remove old sessions
    public function garbageCollection(){
        return
            $this->repository->remove([
                "WHERE" => "session_create_time < '" . date(\App\Entity\Session::DATE_FORMAT, time() - ini_get("session.gc_maxlifetime")) . "'"
            ]);

        // ToDO - function also should remove file from tmp/ sess_[key]
    }

    // remove current session
    public function destroy(){
            // 1 remove session fingerprint in database
        if(isset($_SESSION['user']))
            $this->repository->remove([
                "WHERE" => "user_nick = '{$_SESSION['user']->getNick()}'"
            ]);
        unset($this->session);

            // 2 destroy data in server's file
        session_unset();
        session_destroy();

            // 3 destroy browser data
            //   in session_start(), function don't read ID from cookie and successfully generate new token value
        setcookie('PHPSESSID', "", time() - ini_get('session.gc_maxlifetime'), "/");
    }

    // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    public function regenerateID(bool $removeOldSession = true)
    {
        session_regenerate_id($removeOldSession);
            // need to manually update cookie sess id value
        setcookie('PHPSESSID', session_id(), time() + ini_get('session.gc_maxlifetime'), "/");

            // fetch new key
        $this->manager->updateSessionKey(session_id());
    }

    public function refreshSessionTime(): void{
        $this->manager->updateCreateTime();
    }

    //#####################################################
    public function updateSessionUser(string $nick): bool{
        if($this->session->getUserNick() !== NULL)
            return TRUE;

        $this->manager->updateUserNick($nick);
        $this->manager->updateCreateTime(NULL);

        return $this->refreshSessionEntity();
    }

    public function refreshSessionEntity(): bool{
        return $this->repository->update($this->session, [
            "WHERE" => NULL,
            "AND" => ["user_ip = '{$this->session->getUserIP()}'", "browser_data = '{$this->session->getBrowserData()}'"]
        ]);
    }

    //-----------------------------------------------------
    private function fetchSession(){
        if(isset($_COOKIE['PHPSESSID']))
            return $this->repository->fetchBySessionKey($_COOKIE['PHPSESSID']);
        else
            return $this->repository->find(array(),[
                "WHERE" => NULL,
                "AND" => ["user_ip = '{$_SERVER["REMOTE_ADDR"]}'", "browser_data = '{$_SERVER["HTTP_USER_AGENT"]}'"]
            ])->current();  // return first object from generator's array, tehnically there MUST be ONE object with this criteria in this case
    }
}