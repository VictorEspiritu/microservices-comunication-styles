<?php

use Common\FailRequest;
use Common\Sleep;

require __DIR__ . '/../../../vendor/autoload.php';

Sleep::millisecondsBetween(1, 1000);

FailRequest::oneOutOf(2);

echo "I'm a bit flaky";
