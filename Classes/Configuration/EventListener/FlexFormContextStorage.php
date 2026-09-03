<?php 

declare(strict_types=1);

namespace Theolangstraat\Dailyverses\Configuration\EventListener;

class FlexFormContextStorage
{
    protected static array $storage = [];

    public static function set(string $key, mixed $value): void
    {
        self::$storage[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return self::$storage[$key] ?? null;
    }

    public static function reset(): void
    {
        self::$storage = [];
    }
}
