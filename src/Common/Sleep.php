<?php
declare(strict_types = 1);

namespace Common;

final class Sleep
{
    public static function millisecondsBetween(int $atLeast, int $atMost)
    {
        usleep(rand($atLeast * 1000, $atMost * 1000));
    }
}
