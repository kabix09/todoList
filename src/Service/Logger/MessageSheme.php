<?php
namespace App\Service\Logger;

class MessageSheme
{
    private string $userFingerprint;
    private string $className;
    private string $functionName;
    private bool $personalLog;

    public function __construct(string $userFingerprint, string $className, string $functionName, bool $personalLog = FALSE)
    {
        $this->userFingerprint = $userFingerprint;
        $this->className = $className;
        $this->functionName = $functionName;
        $this->personalLog = $personalLog;
    }

    public function getUserFingerprint(): string {
        return $this->userFingerprint;
    }

    public function getClassName(): string {
        return $this->className;
    }

    public function getFunctionName(): string {
        return $this->functionName;
    }

    public function isPersonalLog(): bool {
        return $this->personalLog;
    }
}