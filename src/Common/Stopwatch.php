<?php
declare(strict_types = 1);

namespace Common;

final class Stopwatch
{
    private static $startTime;

    /**
     * Start the stopwatch
     */
    public static function start(): void
    {
        self::$startTime = self::currentTime();
    }

    /**
     * Stop the stopwatch
     *
     * @return int The number of milliseconds that has passed since the stopwatch was started
     */
    public static function stop(): int
    {
        if (self::$startTime === null) {
            throw new \LogicException('You have to start the stopwatch first');
        }

        $elapsedTimeInMs = (int)round((self::currentTime() - self::$startTime) * 1000);

        self::$startTime = null;

        return $elapsedTimeInMs;
    }

    private static function currentTime(): float
    {
        return microtime(true);
    }
}
