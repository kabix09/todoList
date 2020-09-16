<?php
namespace App\Module\Observer;

interface Observer
{
    public function update(Observable $observable);
}