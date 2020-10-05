<?php
namespace App\Logger;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger{
    const LOG_FILE_PATH = 'H:/xampp/tmp/todoList_log';
    const LOG_DEFAULT_FORMAT = "[%s] [%s] [%s] [%s::%s] - %s\r\n";

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
        try{
                // every time check path
            if(!$this->check($context['personalLog'] ?? FALSE, $context['userFingerprint'] ?? NULL))
            {
                throw new \ErrorException("Failed to open log folder/file - {$context['userFingerprint']}");
            }

                // append content
            $fileHandler = fopen($this->fullPath, "a");

            fwrite($fileHandler, $this->createLog(
                $context['userFingerprint'],
                $level,
                $context['className'],
                $context['functionName'],
                $message
            ));

            fclose($fileHandler);
        }catch(\Exception $e)
        {
            $this->critical($e->getMessage(), [
                "userFingerprint" => $_SERVER['REMOTE_ADDR'],
                "fileName" => __FILE__
            ]);
            var_dump($e->getFile() . " " . $e->getMessage());
            die();
        }

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

            if(!$this->checkFile($fullPath, FALSE))     // flag - don't throw error and  let create sub directory
            {
                if(!mkdir($fullPath))   // try create if not exists
                    return FALSE;
            }
        }


            // 3 DON'T check FULL path - if file don't exists logger create new one
        $fullPath .= $this->loggerFileName;

            // 4 set correct path
        $this->fullPath = $fullPath;
        return TRUE;
    }

    private function checkFile(string $path, bool $flag = TRUE){
        if(!is_dir($path))
            if($flag)
                throw new \RuntimeException("Log folder/file don't exists - {$path}");
            else
                return FALSE;

        if(!is_writable($path))
            if($flag)
                throw new \RuntimeException("Target log folder/file is not writable - {$path}");
            else
                return FALSE;

        return TRUE;
    }

    public function createLog(string $userFingerprint, string $logLevel, string $className, string $functionName, string $message)
    {
        return
            sprintf($this->logFormat,
                (new \DateTime())->format("l, d-m-Y H:i:s") . " UTC " . (new \DateTime())->format("P"),
                $userFingerprint,
                strtoupper($logLevel),
                $className,
                $functionName,
                $message
            );
    }

}