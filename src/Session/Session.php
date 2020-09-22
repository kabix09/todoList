<?php
namespace App\Session;
use App\Session\Counter\Counter;

class Session implements \ArrayAccess
{
    const DEFAULT_REQUESTS_COUNT = 5;
    const DEFAULT_SESSION_DURATION_TIME = 120;   // two minute

    private int $sessionDurationTime;
    public Counter $counter;

    public function __construct(?int $sessionDurationTime = NULL, ?int $sessionRequestsAmount = NULL)
    {
        $this->sessionDurationTime = $sessionDurationTime ?? self::DEFAULT_SESSION_DURATION_TIME;

        session_start();

        $this->counter = Counter::init(
                                $sessionRequestsAmount ?? self::DEFAULT_REQUESTS_COUNT,
                                $_SESSION['counter'] ?? NULL
                                    );
        $this->initSessionTime();
    }

    /*public function __set($name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function __get($name = NULL)
    {
        return $_SESSION[$name];
    }*/

    public function getSession(): ?array
    {
        if(isset($_SESSION))
            return $_SESSION;

        return NULL;
    }

    public function destroy(){
        session_unset();
        session_destroy();
    }

    public function regenerateID(bool $removeOldSession = false)
    {
        session_regenerate_id($removeOldSession);
            // need to manually update cookie sess id value
        setcookie('PHPSESSID', session_id(), time() + 3600, "/");
    }

    //#####################################################
    public function checkUserAgent(): bool{
        if(isset($_SESSION['USER_AGENT']))
        {
            if($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT'])
                return FALSE;
        }else
            $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

        return TRUE;
    }

    public function checkUserIP(): bool{
        if(isset($_SESSION['USER_IP'])){
            if($_SESSION['USER_IP'] !== $_SERVER['REMOTE_ADDR'])
                return FALSE;
        }else
            $_SESSION['USER_IP'] = $_SERVER['REMOTE_ADDR'];

        return TRUE;
    }

    public function isLoginSessionExpired(): bool{
        if(isset($_SESSION['session_time']))
        {
            if(isset($_SESSION['user']) &&
                (time() - $_SESSION['session_time']) > $this->sessionDurationTime) {
                return TRUE;
            }


        }else
            $this->refreshSessionTime();

        return FALSE;
    }

    //-----------------------------------------------------
    public function initSessionTime(): void{
        if(!isset($_SESSION['session_time']))
            $this->refreshSessionTime();
    }

    public function refreshSessionTime(): void{
        $_SESSION['session_time'] = time();
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