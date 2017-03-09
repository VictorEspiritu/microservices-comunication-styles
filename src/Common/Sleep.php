<?php
declare(strict_types = 1);

namespace Common;

use Assert\Assertion;

final class Sleep
{
    /**
     * @param int $atLeast Sleep at least n milliseconds
     * @param int $atMost Sleep at most n milliseconds
     */
    public static function millisecondsBetween(int $atLeast, int $atMost) : void
    {
        Assertion::greaterOrEqualThan($atLeast, 0);
        Assertion::greaterOrEqualThan($atMost, 0);
        Assertion::greaterOrEqualThan($atMost, $atLeast);

        usleep(rand($atLeast * 1000, $atMost * 1000));
    }
}
