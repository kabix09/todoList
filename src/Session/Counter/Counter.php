<?php declare(strict_types=1);
namespace App\Session\Counter;

final class Counter
{
    const DEFAULT_MAX_VALUE = 10;

    static Counter $instance;
    private static int $item;
    private static int $maxValue;

    private function __construct(?int $maxValue = NULL, ?int $startValue = NULL)
    {
        self::$maxValue = $maxValue ?? self::DEFAULT_MAX_VALUE;
        $startValue ? self::$item = $startValue : self::resetItem();
    }

    static function init(?int $maxValue = NULL, ?int $startValue = NULL) : Counter
    {
        self::$instance = new Counter($maxValue, $startValue);
        return self::$instance;
    }

    public static function resetItem(): void{
        self::$item = 0;
    }

    public static function increaseItem(): void{
        self::$item++;
    }

    public static function getItem(): int{
        return self::$item;
    }

    function checkCounter(): bool{
        if(self::$item < self::$maxValue)
            return TRUE;

        return FALSE;
    }
}