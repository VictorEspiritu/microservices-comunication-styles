<?php
declare(strict_types = 1);

namespace Common;

final class StatelessStopwatch
{
    /**
     * Start the stopwatch
     *
     * @return float The time when the stopwatch started
     */
    public static function start(): float
    {
        return self::currentTime();
    }

    /**
     * Stop the stopwatch
     *
     * @param float $startTime The time when the stopwatch started
     * @return int The number of milliseconds that has passed since the stopwatch was started
     */
    public static function stop(float $startTime): int
    {
        $microsecondsPassed = self::currentTime() - $startTime;
        $millisecondsPassed = (int)round($microsecondsPassed * 1000);

        return $millisecondsPassed;
    }

    private static function currentTime(): float
    {
        return microtime(true);
    }
}
