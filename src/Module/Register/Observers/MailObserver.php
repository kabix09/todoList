<?php
namespace App\Module\Login\Observers;

use App\Module\Observer\Observable;
use App\Module\Register\Register;
use App\Entity\User;

class MailObserver extends RegisterObserver
{

    public function update(Observable $observable)
    {
        if($observable->getProcessStatus() === "correct")
        {
            $user = $observable->getUser();

            /*
            $mail = new Mail(
                $user->getEmail(),
                "New Account verification",
                "Thanks for using our service :)"
            );

            $mail->setHeaders();

            try{
                //$mail->send();
            }catch (\Exception $e)
            {
                var_dump($e->getMessage());
                die();
            }
            */
        }
    }

    public function doUpdate(Register $register)
    {
        if($register === $this->register)
            $this->doUpdate($register);
    }
}