<?php
namespace App\Session;
use App\Connection\Connection;
use App\Repository\SessionRepository;
use App\Session\Counter\Counter;

class Session implements \ArrayAccess
{
    const DEFAULT_REQUESTS_COUNT = 5;
    const DEFAULT_SESSION_DURATION_TIME = 120;   // two minute

    private int $sessionDurationTime;
    private SessionRepository $repository;
    private ?\App\Entity\Session $session;
    public Counter $counter;


    public function __construct(?int $sessionDurationTime = NULL, ?int $sessionRequestsAmount = NULL)
    {
        $this->sessionDurationTime = $sessionDurationTime ?? self::DEFAULT_SESSION_DURATION_TIME;


        $this->repository = new SessionRepository(new Connection(include DB_CONFIG));

        $this->init();


        $this->counter = Counter::init(
                                $sessionRequestsAmount ?? self::DEFAULT_REQUESTS_COUNT,
                                $_SESSION['counter'] ?? NULL
                                    );
    }

    private function init(): void{   // TODO - add 0 garbage cllection -> remove old sessions in db ???
        $this->session = NULL;
            // 1 download session object from database
        if(isset($_COOKIE['PHPSESSID']))
        {
            $this->session = $this->repository->fetchBySessionKey($_COOKIE['PHPSESSID']);
        }else{
            $this->session = $this->repository->find(array(),[
                "WHERE" => NULL,
                "AND" => ["user_ip = '{$_SERVER["REMOTE_ADDR"]}'", "browser_data = '{$_SERVER["HTTP_USER_AGENT"]}'"]
            ])->current();  // return first object from generator's array, tehnically there MUST be ONE object with this criteria in this case
        }
            // 2 if not exist, create new session bd object
        if(is_null($this->session))
        {
                // create new session object
            $sessionManager = new \App\Manager\SessionManager(NULL);
            $sessionManager->init();
            $this->session = $sessionManager->return();

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
            // in purpose to overwrite time in cookie PHPSESSID with time()-3600 in destroy() func
            setcookie('PHPSESSID', session_id(), time() + 3600, "/");   // delegate create cookie task to other class?

                // 4 update session instance in database (key & session time) if that is a new session
            if(session_id() !== $this->session->getSessionKey())
            {
                $sessionManager = new \App\Manager\SessionManager(NULL);
                $sessionManager->init();
                $sessionManager->updateSessionKey(session_id());
                $session = $sessionManager->return();

                if($this->repository->update($session, [
                        "WHERE" => NULL,
                        "AND" => ["user_ip = '{$session->getUserIP()}'", "browser_data = '{$session->getBrowserData()}'"]
                    ]) === FALSE)
                    throw new \Exception("couldn't update session instance in database");
            }
        }

            // 5 download correct session instance from
        $this->session = $this->repository->find(array(),[
            "WHERE" => NULL,
            "AND" => ["user_ip = '{$_SERVER["REMOTE_ADDR"]}'", "browser_data = '{$_SERVER["HTTP_USER_AGENT"]}'"]
        ])->current();
    }

    public function getSession(): ?array
    {
        if(isset($_SESSION))
            return $_SESSION;

        return NULL;
    }

    public function destroy(){
        session_unset();
        session_destroy();
            // in session_start(), function don't read ID from cookie and successfully generate new token value
        setcookie('PHPSESSID', "", time() - 3600, "/");
    }

    public function regenerateID(bool $removeOldSession = true)
    {
        session_regenerate_id($removeOldSession);
            // need to manually update cookie sess id value
        setcookie('PHPSESSID', session_id(), time() + 3600, "/");

            // fetch new key
        $this->session->setSessionKey(session_id());
    }

    //#####################################################
    public function checkUserAgent(): bool{
        return
            ($this->session->getBrowserData() === $_SERVER['HTTP_USER_AGENT']);
    }

    public function checkUserIP(): bool{
        return
            ($this->session->getUserIP() === $_SERVER['REMOTE_ADDR']);
    }

    public function isLoginSessionExpired(): bool{
        if(isset($_SESSION['user']) &&
            (time() - \DateTime::createFromFormat(\App\Entity\Session::DATE_FORMAT, $this->session->getCreateTime())->getTimestamp()) > $this->sessionDurationTime) {
                return TRUE;
        }

        return FALSE;
    }

    //-----------------------------------------------------

    public function refreshSessionEntity(): void{
        $sessionManager = new \App\Manager\SessionManager($this->session);
        $sessionManager->updateCreateTime();

        $this->repository->update($sessionManager->return(), [
            "WHERE" => NULL,
            "AND" => ["user_ip = '{$this->session->getUserIP()}'", "browser_data = '{$this->session->getBrowserData()}'"]
        ]);
    }

    //**************************************************
    public function offsetExists($offset) : bool
    {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $_SESSION[$offset] : NULL;
    }

    public function offsetSet($offset, $value)
    {
        if(is_null($offset))
            $_SESSION[] = $value;
        else
            $_SESSION[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if(is_null($offset))
            unset($_SESSION);
        else
            unset($_SESSION[$offset]);
    }
}