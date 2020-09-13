<?php
namespace App\Entity\Factory;

use App\Entity\ {Base, User};

final class UserFactory extends BaseFactory
{
    public static function arrayToEntity(array $data): ?Base
    {
        $name = self::targetClass();
        return parent::doArrayToEntity($data, new $name());
    }

    public static function entityToArray($objectInstance) : ?array{
        if($objectInstance instanceof User)
            return parent::doEntityToArray($objectInstance);

        return NULL;
    }

    public static function targetClass(): string
    {
        return User::class;
    }

    public function newUser() : User{
        return new User();
    }
}