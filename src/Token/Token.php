<?php
namespace App\Token;

class Token
{
    protected string $token;

    public function __construct(string $token = '')
    {
        $this->token = $token;
    }

    public function generate(int $length = 16) : Token{
        $this->token = random_bytes($length);
        return $this;
    }

    public function binToHex() : Token{
        $this->token = sodium_bin2hex($this->token);
        return $this;
    }

    public function hexToBin() : Token{
        $this->token = sodium_hex2bin($this->token);
        return $this;
    }

    public function encode() : Token{
        $this->token = base64_encode($this->token);
        return $this;
    }

    public function decode() : Token{
        $this->token = base64_decode($this->token);
        return $this;
    }

    public function hash() : Token {
        $this->token = sodium_crypto_generichash($this->token);
        return $this;
    }

    public function getToken() : string {
        return $this->token;
    }
}