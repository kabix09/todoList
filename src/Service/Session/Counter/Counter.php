<?php declare(strict_types=1);

namespace App\Service\Session\Counter;

use App\Service\Logger\Logger;
use App\Service\Logger\MessageSheme;

final class Counter
{
    private const DEFAULT_MAX_VALUE = 10;

    public static Counter $instance;
    private static int $item;
    private static int $maxValue;

    private function __construct(int $maxValue, int $startValue)
    {
        self::$maxValue = $maxValue ?: self::DEFAULT_MAX_VALUE;
        self::$item = $startValue;
    }

    public static function init(int $maxValue = 0, int $startValue = 0) : Counter
    {
        if(empty(self::$instance)) {
            self::$instance = new self($maxValue, $startValue);
        }
        return self::$instance;
    }

    public static function resetItem(): void
    {
        self::$item = 0;
    }

    public static function increaseItem(): void
    {
        self::$item++;
    }

    public static function getItem(): int
    {
        return self::$item;
    }

    public static function checkCounter(): bool
    {
        var_dump("cur: " . self::$item);
        var_dump("max: " . self::$maxValue);
        if(self::$item < self::$maxValue) {
            return TRUE;
        }

        return FALSE;
    }
}