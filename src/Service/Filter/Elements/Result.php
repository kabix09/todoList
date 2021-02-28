<?php
namespace App\Service\Filter\Elements;

final class Result
{
    protected $flag;
    protected $item;    // handle input value or bool result
    protected $messages = array();  // handle array of messages

    public function __construct($value, $messages, $flag = TRUE)
    {
        $this->item = $value;
        $this->flag = $flag;

        if(is_array($messages))
            $this->messages = $messages;
        else
            $this->messages = [$messages];
    }

    public function mergeResults(Result $result){

        //if (is_bool($this->item) && is_bool($result->item))
        //    $this->item = ($this->item && $result->item);   // if anywhere value is false then result will be false
        //else
        $this->item = $result->item;
        $this->flag = ($this->flag && $result->flag);
        $this->mergeMessages($result);
    }

    public function mergeMessages(Result $result){
        if(isset($result->messages) && is_array($result->messages))
        {
            $this->messages = array_merge($this->messages, $result->messages);
        }
    }

    /**
     * @param $newValue
     */
    public function setItem($newValue) : void{
        $this->item = $newValue;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}