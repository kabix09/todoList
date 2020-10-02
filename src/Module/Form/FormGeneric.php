<?php
namespace App\Module\Form;

use App\Filter\Filter;
use App\Logger\Logger;
use App\Module\Observer\Observable;
use App\Module\Observer\Observer;
use App\Repository\BaseRepository;
use App\Token\Token;

abstract class FormGeneric implements Observable
{
    const PROCESS_STATUS = ["errors", "correct" , "session"];
    protected ?string $processStatus = NULL;

    private array $observers = [];

    protected $repository;

    protected array $data;  // raw array
    protected $object;     // entity result object
    protected array $errors = [];     // valid / script errors

    protected Logger $logger;

    public function __construct(array $formData, BaseRepository $repository)
    {
        $this->data = $formData;
        $this->repository = $repository;

        $this->logger = new Logger();
    }

    // observable methods
    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer)
    {
        $this->observers = array_filter(
            $this->observers,
            function($object) use ($observer){
                return (!($object === $observer));
            }
        );
    }

    public function notify()
    {
        foreach ($this->observers as $observer)
        {
            $observer->update($this);
        }
    }

        // main method
    public function handler(?string $serverToken = NULL, array $filter, array $assignments): bool{
        try{

            if($this->checkToken($serverToken)){
                if (!$this->validData($filter, $assignments))
                {
                    $this->processStatus = self::PROCESS_STATUS[0];
                }

                $this->doHandler();


                $this->notify();

                if (empty($this->errors))
                    return TRUE;
            }
        }catch (\Exception $e){
            echo '<pre>';
            var_dump($e);
            echo '</pre>';

            $this->logger->error($e->getMessage(), [
                "userFingerprint" => $_SERVER['REMOTE_ADDR'],
                "fileName" => $e->getFile()
            ]);

            die();
        }
        return FALSE;
    }

    abstract protected function doHandler();

            // generic methods
    public function checkToken(?string $serverToken = NULL): bool{
        if(!isset($serverToken))
            throw new \RuntimeException("token doesn't exists on server side ://");

        if(sodium_compare(
                (new Token($serverToken))->hash()->getToken(),
                (new Token($this->data['hidden']))->decode()->getToken()
            ) !== 0
        ) throw new \RuntimeException('detected cross-site attack on login form');

        unset($this->data['hidden']);
        //unset($serverToken);    // when arg is passing by reference -> doesn't work --- WHY ???

        return TRUE;
    }

    public function validData(array $filter, array $assignments): bool{
        $filter = new Filter($filter, $assignments);
        $filter->process($this->data);

        foreach ($filter->getMessages() as $key => $value)
        {
            $this->errors[$key] = $value;
        }

        if(!empty($this->errors))
            return FALSE;

        return TRUE;
    }

    // hermetical methods
    public function getProcessStatus(): string{
        return $this->processStatus;
    }
    public function getErrors(): array{
        return $this->errors;
    }
    public function getObject() {
        return $this->object;
    }
}