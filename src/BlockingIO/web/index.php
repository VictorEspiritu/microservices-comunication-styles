<?php

use Common\Stopwatch;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

require __DIR__ . '/../../../vendor/autoload.php';

Stopwatch::start();

// Make some HTTP requests
$httpClient = new Client();
$allResults = [
    $httpClient->get('http://slow_service1/'),
    $httpClient->get('http://slow_service2/')
];

// Prepare a Response for the current request
$response = new Response(
    'php://memory',
    200,
    ['Content-Type' => 'text/plain']
);

// Use the remote responses to create our own response
foreach ($allResults as $index => $result) {
    /** @var $result ResponseInterface */
    $response->getBody()->write(sprintf(
        "Response %d: %s\n",
        $index,
        $result->getBody()
    ));
}

$response->getBody()->write(sprintf(
    "Total response time: %dms\n",
    Stopwatch::stop()
));

// Send our response to the HTTP client
$emitter = new SapiEmitter();
$emitter->emit($response);
