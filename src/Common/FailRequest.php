<?php
declare(strict_types = 1);

namespace Common;

final class FailRequest
{
    /**
     * @param int $n Fail 1 out of n requests
     */
    public static function oneOutOf(int $n) : void
    {
        if (rand(1, $n) === $n) {
            http_response_code(500);
            exit;
        }
    }
}
