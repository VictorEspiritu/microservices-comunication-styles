<?php

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Io\Sender;
use Common\Stopwatch;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\Http\Request;
use React\Http\Response;
use React\Http\Server as HttpServer;
use function React\Promise\all;
use React\Socket\Server as SocketServer;

require __DIR__ . '/../../../vendor/autoload.php';

$eventLoop = Factory::create();
// Use Docker's embedded DNS server
$httpClient = new Browser($eventLoop, Sender::createFromLoopDns($eventLoop, '127.0.0.11'));

$app = function (Request $request, Response $response) use ($httpClient) {
    echo sprintf("Serving request from %s\n", implode(",", $request->getHeader('User-Agent')));
    Stopwatch::start();

    all([
        $httpClient->get('http://slow_service1/'),
        $httpClient->get('http://slow_service2/')
    ])->then(function (array $results) use ($response) {
        $response->writeHead(200, array('Content-Type' => 'text/plain'));
        foreach ($results as $result) {
            /** @var $result ResponseInterface */
            $response->write(sprintf(
                "First 100 bytes of the response: %s\n",
                $result->getBody()->read(100)
            ));
        }
        $response->write(sprintf(
            "Total response time: %d\n",
            Stopwatch::stop()
        ));
        $response->end();
    }, function ($reason) use ($response) {
        $response->write((string)$reason);
        $response->end();
    });
};

$socket = new SocketServer('0.0.0.0:80', $eventLoop);
$http = new HttpServer($socket, $eventLoop);

$http->on('request', $app);

$eventLoop->run();
