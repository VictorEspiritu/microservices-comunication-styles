<?php

use Common\Sleep;
use Common\Stopwatch;

require __DIR__ . '/../../../vendor/autoload.php';

Stopwatch::start();

Sleep::millisecondsBetween(500, 800);

$durationInMs = Stopwatch::stop();

echo "It took me {$durationInMs}ms to respond";
