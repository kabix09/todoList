<?php declare(strict_types = 1);
namespace App\Connection;
use App\Logger\Logger;
use App\Connection\AbstractFactory\{mysqlFactory, odbcFactory, PDOfactory};
use \PDO;

class Connection extends PDOfactory
{
    private $logger;

    const PDOdrivers = ['odbc'=>'odbc', 'mysql' =>'mysql'];
    private $connection;
    private $data = array();

    public function __construct(array $data)
    {
        $this->logger = new Logger();

        if($this->validData($data))
            $this->setData($data);
    }

    public function connect(array $connectData = array())
    {
        try {
            $pdoInstance = $this->makePDO();

            if (is_null($pdoInstance))
            {
                throw new \RuntimeException("unrecognized PDO driver: this class don't support '" . $this->data['driver'] . "'" . PHP_EOL);
            } else
            {
                $this->setConnection($pdoInstance);
            }
        }catch(\Throwable $e){
            $this->logger->error($e->getMessage(), [
                "userFingerprint" => $_SERVER['REMOTE_ADDR'],
                "fileName" => $e->getFile(),
                "line" => $e->getLine()
            ]);
            die();
        }

    }

    private function makePDO(): ?\PDO {
        switch ($this->data['driver'])
        {
            case self::PDOdrivers['odbc']: {
                return $this->factory(new odbcFactory());
            }
            case self::PDOdrivers['mysql']: {
                return $this->factory(new mysqlFactory());
            }
            default: {
                return NULL;
            }
        }
    }

    private function factory(PDOfactory $PDOfactory){
        $pdo = $PDOfactory->connect($this->data);

        if(is_null($pdo))
            throw new \PDOException("unable to connect with database :/");

        return $pdo;
    }

    protected function checkDriver(string $driver){
        foreach (PDO::getAvailableDrivers() as $allowedDriver)
            if($allowedDriver === $driver)
                return TRUE;

        return FALSE;
    }

    private function validData(array $data) : bool{
        if (count($data) < 5)
            throw new \RuntimeException("invalid arguments: incorrect number of parameters" . PHP_EOL);

        if (!isset($data['driver']))
            throw new \RuntimeException("invalid arguments: driver wasn't indicated" . PHP_EOL);

        if (!$this->checkDriver($data['driver']))
            throw new \RuntimeException("unrecognized PDO driver: your server don't support '" . $data['driver'] . "' driver extension" . PHP_EOL);

        return TRUE;
    }

    public function setData(array $data){
        $this->data = $data;
    }

    private function setConnection(\PDO $connection){
        $this->connection = $connection;
    }

    public function getConnection(): ?\PDO{
        return $this->connection;
    }
}