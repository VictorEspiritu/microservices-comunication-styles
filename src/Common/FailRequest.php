<?php
declare(strict_types = 1);

namespace Common;

final class FailRequest
{
    public static function oneOutOf($n)
    {
        if (rand(1, $n) == $n) {
            http_response_code(500);
            exit;
        }
    }
}
