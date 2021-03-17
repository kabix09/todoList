<?php
namespace App\Entity\Mapper;

use App\Entity\ {Base, User};

final class UserMapper extends BaseMapper
{
    public static function convertArrayToEntity(array $data): ?Base
    {
        $name = self::targetClass();
        return parent::arrayToEntity($data, new $name());
    }

    public static function convertEntityToArray($objectInstance) : ?array
    {
        if($objectInstance instanceof User)
            return parent::entityToArray($objectInstance);

        return NULL;
    }

    public static function overwriteEntity($sourceObject, $destinationObject): bool
    {
        if(!$sourceObject instanceof User || !$destinationObject instanceof User)
            throw new \InvalidArgumentException("Arguments instance must be " . self::targetClass());

        parent::overwrite($sourceObject, $destinationObject);

        return TRUE;
    }

    public static function targetClass(): string
    {
        return User::class;
    }


}