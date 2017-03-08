<?php
declare(strict_types = 1);

namespace Common;

final class Stopwatch
{
    private static $startTime;

    public static function start()
    {
        self::$startTime = self::currentTime();
    }

    public static function stop(): int
    {
        if (self::$startTime === null) {
            throw new \LogicException('You have to start the stopwatch first');
        }

        $elapsedTimeInMs = (int)round((self::currentTime() - self::$startTime) * 1000);

        self::$startTime = null;

        return $elapsedTimeInMs;
    }

    private static function currentTime()
    {
        return microtime(true);
    }
}
