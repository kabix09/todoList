<?php
namespace App\Access;
interface TaskParameters
{
    public const ID = "ID";
    public const OWNER = "OWNER";
    public const QUERY_VARIABLES = [self::ID => "id", self::OWNER => "owner"];
}