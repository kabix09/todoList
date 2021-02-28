<?php
namespace App\Service\Filter;

use UnexpectedValueException;

abstract class AbstractFilter
{
    const BAD_CALLBACK = 'Value must be e.g function or callback';
    const DEFAULT_MESSAGE_FORMAT = '%20s : %60s';
    const DEFAULT_SEPARATOR = '</br>' . PHP_EOL;
    const DEFAULT_MISSING_MESSAGE = 'Missing Item';

    protected $callbacks;
    protected $assignments;
    protected $results = array();

    private $messageSeparator;
    private $missingMessage;

    /*
     * callbacks - it is a list of defined actions e.g
     *      'trim' =>
     *          new class() { public function __invoke() { ... }}
     * assignments - it is a list that defines the (form) fields and their constraints e.g
     *      'first_name' => [
                ['key' => 'length', 'params' => ['length' => 128]]

     */
    public function __construct(array $callbacks, array $assignments, string $defaultSeparator = NULL, string $defaultMissingMessage = NULL)
    {
        $this->setCallbacks($callbacks);
        $this->setAssignments($assignments);

        $this->setMessageSeparator($defaultSeparator ?? self::DEFAULT_SEPARATOR);
        $this->setMissingMessage($defaultMissingMessage ?? self::DEFAULT_MISSING_MESSAGE);
    }

            // -------- callbacks --------
    /**
     * @return mixed
     */
    public function getCallbacks() : ?array{
        return $this->callbacks;
    }

    public function getCallback(string $key) : ?callable
    {
        if(array_key_exists($key, $this->callbacks))
            return $this->callbacks[$key];

        return NULL;
    }

    public function setCallbacks(array $callbacks): void{
        foreach ($callbacks as $key => $value)
            $this->setCallback($key, $value);
    }

    public function setCallback(string $key, $callback): void{
        if($callback instanceof CallbackInterface)
            $this->callbacks[$key] = $callback;
        else
            throw new UnexpectedValueException(self::BAD_CALLBACK);
    }

    public function removeCallback(string $key) : bool{
        if (isset($this->callbacks[$key]))
        {
            unset($this->callbacks[$key]);
            return TRUE;
        }
        return FALSE;
    }

            // -------- results --------
    public function getResults() : array{
        return $this->results;
    }

            // -------- messages --------
    public function getMessages(){

        foreach ($this->results as $key => $item)
        {
            if($item->getMessages()) yield $key => $item->getMessages();
        }

        return array();
    }

    /**
     * @param array $assignments
     */
    public function setAssignments(array $assignments): void
    {
        $this->assignments = $assignments;
    }

            // ----------------    -------------    ----------------
    /**
     * @param string $messageSeparator
     */
    public function setMessageSeparator(string $messageSeparator): void
    {
        $this->messageSeparator = $messageSeparator;
    }

    /**
     * @param string $missingMessage
     */
    public function setMissingMessage(string $missingMessage): void
    {
        $this->missingMessage = $missingMessage;
    }


            // -------- ---- functions ---- --------
    public function getMessageString($width = 80, $format = NULL){
        if(!$format)
            $format = self::DEFAULT_MESSAGE_FORMAT . $this->messageSeparator;
        $output = '';
        if($this->results){
            foreach ($this->results as $key => $value) {
                if ($value->getMessages()) {
                    foreach ($value->getMessages() as $message)
                        $output .= sprintf($format, $key, trim($message));
                }
            }
        }
        return $output;
    }

    public function getItemsAsArray()
    {
        $return = array();
        if ($this->results) {
            foreach ($this->results as $key => $item)
                $return[$key] = $item->getItem();
        }
        return $return;
    }
}