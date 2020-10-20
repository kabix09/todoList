<?php
namespace App\Access;

interface FormStatus
{
    public const GET = "GET";
    public const POST = "POST";
    public const OTHER = "OTHER";
    public const STATUS = [self::GET => "GET", self::POST => "POST", self::OTHER => "OTHER"];
}