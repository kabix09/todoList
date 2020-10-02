<?php
namespace App\Logger;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger{
    const LOG_FILE_PATH = 'H:/xampp/tmp/todoList_log';
    const LOG_DEFAULT_FORMAT = "[%s] - %s - %s : %s line: %s - %s\r\n";

    private string $filePath;
    private string $logFormat;

    private string $loggerFileName;
    protected string $fullPath;

    public function __construct(?string $filePath = NULL, ?string $logFormat = NULL)
    {
        $this->filePath = $filePath ?? self::LOG_FILE_PATH;
        $this->logFormat = $logFormat ?? self::LOG_DEFAULT_FORMAT;

        $this->loggerFileName = (new \DateTime())->format("d-m-Y") . ".txt";
    }

    public function log($level, $message, array $context = array())
    {
            // every time check path
        if(!$this->check($context['personalLog'] ?? FALSE, $context['userFingerprint'] ?? NULL))
            throw new \ErrorException("failed to open log file");

            // append content
        $fileHandler = fopen($this->fullPath, "a");

        fwrite($fileHandler, $this->createLog(
            $context['userFingerprint'],
            $level,
            $context['fileName'],
            $context['line'],
            $message
        ));

        fclose($fileHandler);
    }

    private function check(bool $flag = FALSE, ?string $userFingerprint = NULL) : bool
    {
            // 1 check MAIN path
        $fullPath = $this->filePath . "/";

        if(!$this->checkFile($fullPath))
            return FALSE;


            // 2 check SUB path
        if($flag)
        {
            $fullPath .= $userFingerprint . "/";

            if(!$this->checkFile($fullPath))
                return FALSE;
        }


            // 3 DON'T check FULL path - if file don't exists logger create new one
        $fullPath .= $this->loggerFileName;

            // 4 set correct path
        $this->fullPath = $fullPath;
        return TRUE;
    }

    private function checkFile(string $path){
        try{
            if(!is_dir($path))
                throw new \Exception("folder/file don't exists - {$path}");

            if(!is_writable($path))
                throw new \Exception("target folder/file is not writable - {$path}");

            return TRUE;
        }catch(\Exception $e)
        {
            var_dump($e->getFile() . " " . $e->getMessage());
        }
    }

    public function createLog(string $userFingerprint, string $logLevel, string $fileName, string $line, string $message)
    {
        return
            sprintf($this->logFormat,
                (new \DateTime())->format("H:i:s"),
                $userFingerprint,
                $logLevel,
                $fileName,
                $line,
                $message
            );
    }

}