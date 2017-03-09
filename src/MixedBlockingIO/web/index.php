<?php

use function Clue\React\Block\awaitAll;
use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Io\Sender;
use Common\Stopwatch;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

require __DIR__ . '/../../../vendor/autoload.php';

Stopwatch::start();

$eventLoop = Factory::create();
// Use Docker's embedded DNS server
$httpClient = new Browser($eventLoop, Sender::createFromLoopDns($eventLoop, '127.0.0.11'));

// Prepare a Response for the current request
$response = new Response(
    'php://memory',
    200,
    ['Content-Type' => 'text/plain']
);

// Create promises for the HTTP requests we're going to make
$promises = [
    $httpClient->get('http://slow_service1/'),
    $httpClient->get('http://slow_service2/')
];

// Wait for all of the promises to resolve
$allResults = awaitAll($promises, $eventLoop);

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
    "Total response time: %d\n",
    Stopwatch::stop()
));

// Send our response to the HTTP client
$emitter = new SapiEmitter();
$emitter->emit($response);
