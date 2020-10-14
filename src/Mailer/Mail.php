<?php
namespace App\Mailer;

class Mail
{
    const SYSTEM_MAIL = '';

    private string $to;
    private string $from;
    private string $subject;
    private string $content;

    private string $headers = "";
    private $headersPattern = "From: %s"."\r\n". "X-Sender: %s"."\r\n". "X-Priority: %s"."\r\n".  "X-Mailer: PHP/%s";

    public function __construct(string $to, string $subject, string $content, ?string $from = NULL)
    {
        $this->to = $to;
        $this->from = $from ?? self::SYSTEM_MAIL;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function setHeaders(bool $htmlEmail = FALSE) : void {

            // set content-type when sending HTML email
        if($htmlEmail)
        {
            $this->headers .= "MIME-Version: 1.0" . "\r\n";
            $this->headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";

            $this->replaceNewLine();
        }

        $this->headers .= sprintf($this->headersPattern,
                                    $this->from,
                                    $this->from,
                                    3,
                                    phpversion());
    }

    public function send(): bool{
        return
            mail($this->to, $this->subject, $this->content, $this->headers);
    }

    private function replaceNewLine()
    {
        $this->content = str_replace( ["\r\n", "\n\r"], '<br>', $this->content);
    }
}