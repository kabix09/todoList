<?php declare(strict_types=1);
namespace App\Service\Mail;

use ConnectionFactory\Validator\Validator;

class MailParamsValidator implements Validator
{
    private const DATA_KEYS = ['smtp', 'port', 'username', 'password', 'from', 'replyTo'];

    public function __construct(array $options = []) { }

    public function validate(array $dataToValid): array
    {
        $dnsData = $this->validSmtpData($dataToValid);

        if(!$this->checkSmptData($dnsData)) {
            throw new \InvalidArgumentException('Invalid SMTP array data argument');
        }
        return $dnsData;
    }

    private function validSmtpData(array $data) : array
    {
        $validData = [];

        foreach (self::DATA_KEYS as $key) {
            $validData[$key] = $data[$key] ?? null;
        }

        return $validData;
    }

    private function checkSmptData(array $data) : bool
    {
        foreach (self::DATA_KEYS as $key) {
            if(is_null($data[$key]))
            {
                return false;
            }
        }
        return true;
    }
}