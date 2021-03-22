<?php declare(strict_types=1);

namespace App\Service\Session\Counter;

final class Counter
{
    private const DEFAULT_MAX_VALUE = 10;

    public static Counter $instance;
    private static int $item;
    private static int $maxValue;

    private function __construct(?int $maxValue = NULL, ?int $startValue = NULL)
    {
        self::$maxValue = $maxValue ?? self::DEFAULT_MAX_VALUE;
        $startValue ? self::$item = $startValue : self::resetItem();
    }

    static function init(?int $maxValue = NULL, ?int $startValue = NULL) : Counter
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
        if(self::$item < self::$maxValue) {
            return TRUE;
        }

        return FALSE;
    }
}