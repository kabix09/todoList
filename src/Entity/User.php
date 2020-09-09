<?php
namespace App\Entity;

final class User extends Base
{
    const TABLE_NAME = "user";
    const STATUS = ["active", "inactive", "blocked"];

    const MAPPING = [
        "nick" => "nick",
        "email" => "email",
        "password" => "password",
        "last_login_date" => "lastLoginDate",
        "create_account_date" => "createAccountDate",
        "account_status" => "status",
        "end_ban" => "endBan"
    ];

    private string $nick;
    private string $email;
    private string $password;
    private string $lastLoginDate;
    private string $createAccountDate;
    private string $status;
    private ?string $endBan;

    public function __construct()
    {
        $this->nick = "";
        $this->email = "";
        $this->password = "";
        $this->lastLoginDate = "";
        $this->createAccountDate = "";
        $this->status = "";
    }

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function removePassword():void{
        unset($this->password);
    }
    /**
     * @return ?string
     */
    public function getLastLoginDate(): ?string
    {
        return $this->lastLoginDate;
    }

    /**
     * @param string|null $lastLoginDate
     * @throws \Exception
     */
    public function setLastLoginDate(?string $lastLoginDate = NULL): void
    {
        $this->lastLoginDate = $lastLoginDate;
    }

    /**
     * @return ?string
     */
    public function getCreateAccountDate(): ?string
    {
        return $this->createAccountDate;
    }

    /**
     * @param string|null $createAccountDate
     * @throws \Exception
     */
    public function setCreateAccountDate(?string $createAccountDate = NULL): void
    {
        $this->createAccountDate = $createAccountDate;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getEndBan(): ?string
    {
        return $this->endBan;
    }

    /**
     * @param string $endBan
     */
    public function setEndBan(?string $endBan): void
    {
        $this->endBan = $endBan;
    }

}