<?php

use CircuitBreakerBlockingIO\Application;

require __DIR__ . '/../../../vendor/autoload.php';

$application = new Application();

try {
    $command = $application->createRequestFlakyServiceCommand();
    $response = $command->execute();

    header('Content-Type: text/plain');
    echo "Response: {$response}";
} catch (\Throwable $fault) {
    error_log($fault->getMessage());
    http_response_code(500);
}
