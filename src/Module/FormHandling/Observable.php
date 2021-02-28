<?php
namespace App\Module\FormHandling;

use App\Module\Observer\Observer;
use App\Module\Observer\Observable as ObservableInterface;

class Observable implements ObservableInterface
{
    protected array $observers = [];

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
}