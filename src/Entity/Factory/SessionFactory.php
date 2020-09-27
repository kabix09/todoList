<?php
namespace App\Entity\Factory;

use App\Entity\{Base, Session};

class SessionFactory extends BaseFactory
{

    public static function targetClass(): string
    {
        return Session::class;
    }

    public static function arrayToEntity(array $data)
    {
        $name = self::targetClass();
        return parent::doArrayToEntity($data, new $name());
    }

    public static function entityToArray(Base $objectInstance): ?array
    {
        if($objectInstance instanceof Session)
            return parent::doEntityToArray($objectInstance);

        return NULL;
    }

    public function newSession(){
        return new Session();
    }
}