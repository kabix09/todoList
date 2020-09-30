<?php
namespace App\Manager;

use App\Entity\Factory\SessionFactory;
use App\Entity\Session;

class SessionManager
{
    private Session $session;
    //private SessionRepository $repository;

    public function __construct($data = NULL)
    {
        if(is_array($data))
            $this->session = SessionFactory::arrayToEntity($data);
        elseif($data instanceof Session)
            $this->session = $data;
        else
        {
            $this->session = (new SessionFactory())->newSession();
            $this->init();
        }


        //$this->repository = $userRepository;
    }



    public function set(Session $session){
        foreach (get_class_methods(Session::class) as $functionName)
        {
            if(strpos($functionName, "set") === 0)
            {
                $getter = "get" . substr($functionName, 3);
                $this->session->$functionName($session->$getter());
            }

        }
    }
    public function return():Session{
        return $this->session;
    }

    public function init(){
        $this->setUserIP();
        $this->setBrowserData();

        //$this->updateCreateTime();
    }

    private function setUserIP(){
        $this->session->setUserIP($_SERVER['REMOTE_ADDR']);
    }
    private function setBrowserData(){
        $this->session->setBrowserData($_SERVER['HTTP_USER_AGENT']);
    }
    public function updateSessionKey(string $sessionKey, ?Session $session = NULL){
        ($session ?? $this->session)->setSessionKey($sessionKey);
    }
    public function updateUserNick(?string $userNick = NULL, ?Session $session = NULL){
        ($session ?? $this->session)->setUserNick($userNick);
    }
    public function updateCreateTime(?string $newTime = NULL, ?Session $session = NULL){
        if(is_null($newTime))
            $newTime = $this->getDate();

        ($session ?? $this->session)->setCreateTime($newTime);

    }

    private function getDate() : string {
        return
            (new \DateTime())->format(Session::DATE_FORMAT);
    }
}