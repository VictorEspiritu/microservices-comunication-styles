<?php

use CircuitBreakerBlockingIO\Application;

require __DIR__ . '/../../../vendor/autoload.php';

$application = new Application();

try {
    $command = $application->createRequestFlakyServiceCommand();
    $response = $command->execute();

    header('Content-Type: text/plain');
    echo $response;
    exit;
} catch (\Throwable $fault) {
    http_response_code(500);
    echo $fault;
    exit;
}
