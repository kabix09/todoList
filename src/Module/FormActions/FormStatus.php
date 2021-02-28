<?php
namespace App\Module\FormActions;

interface FormStatus
{
    public const GET = "GET";
    public const POST = "POST";
    public const OTHER = "OTHER";
    public const STATUS = [self::GET => "GET", self::POST => "POST", self::OTHER => "OTHER"];
}