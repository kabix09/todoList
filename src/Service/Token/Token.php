<?php
namespace App\Service\Token;

class Token
{
    protected string $token;

    public function __construct(string $token = '')
    {
        $this->token = $token;
    }

    public function generate(int $length = 32) : Token{
        $this->token = (new \DateTime())->getTimestamp() . random_bytes($length);
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

    public function hash(int $length = 64, string $key = '') : Token {
        $this->token = sodium_crypto_generichash($this->token, $key, $length);
        return $this;
    }

    public function getToken() : string {
        return $this->token;
    }
}