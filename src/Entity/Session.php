<?php
namespace App\Entity;

final class Session extends Base
{
    const TABLE_NAME = "session";
    public const MAPPING = [
        'id' => 'id',
        'session_key' => 'sessionKey',
        'user_nick' => 'userNick',
        'user_ip' => 'userIP',
        'browser_data' => 'browserData',
        'session_create_time' => 'createTime'
    ];

    private string $sessionKey;
    private string $userIP;
    private string $browserData;
    private ?string $createTime;
    private ?string $userNick;

    public function __construct()
    {
        $this->sessionKey = "";
        $this->userIP = "";
        $this->browserData = "";
        $this->createTime = NULL;
        $this->userNick = NULL;
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /**
     * @param string $sessionKey
     */
    public function setSessionKey(string $sessionKey): void
    {
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return string
     */
    public function getUserIP(): string
    {
        return $this->userIP;
    }

    /**
     * @param string $userIP
     */
    public function setUserIP(string $userIP): void
    {
        $this->userIP = $userIP;
    }

    /**
     * @return string
     */
    public function getBrowserData(): string
    {
        return $this->browserData;
    }

    /**
     * @param string $browserData
     */
    public function setBrowserData(string $browserData): void
    {
        $this->browserData = $browserData;
    }

    /**
     * @return string|null
     */
    public function getCreateTime(): ?string
    {
        return $this->createTime;
    }

    /**
     * @param string|null $createTime
     */
    public function setCreateTime(?string $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return string|null
     */
    public function getUserNick(): ?string
    {
        return $this->userNick;
    }

    /**
     * @param string|null $userNick
     */
    public function setUserNick(?string $userNick): void
    {
        $this->userNick = $userNick;
    }
}