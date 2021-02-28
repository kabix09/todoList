<?php
namespace App\Service\Session;

class SessionArray implements \ArrayAccess
{
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

    public function getSession(): ?array
    {
        if(isset($_SESSION))
            return $_SESSION;

        return NULL;
    }
}