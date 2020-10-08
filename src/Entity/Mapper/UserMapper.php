<?php
namespace App\Entity\Mapper;

use App\Entity\ {Base, User};

final class UserMapper extends BaseMapper
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
}