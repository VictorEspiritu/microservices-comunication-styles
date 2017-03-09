<?php

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Io\Sender;
use Common\StatelessStopwatch;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\Http\Request;
use React\Http\Response;
use React\Http\Server as HttpServer;
use function React\Promise\all;
use React\Socket\Server as SocketServer;

require __DIR__ . '/../../../vendor/autoload.php';

$eventLoop = Factory::create();
$httpClient = new Browser(
    $eventLoop,
    // Use Docker's embedded DNS server
    Sender::createFromLoopDns($eventLoop, '127.0.0.11')
);

$app = function (Request $request, Response $response) use ($httpClient) {
    $startTime = StatelessStopwatch::start();
    all([
        $httpClient->get('http://slow_service1/'),
        $httpClient->get('http://slow_service2/')
    ])->then(
        function (array $allResults) use ($response, $startTime) {
            $response->writeHead(200, ['Content-Type' => 'text/plain']);
            foreach ($allResults as $index => $result) {
                /** @var $result ResponseInterface */
                $response->write(sprintf(
                    "Response %d: %s\n",
                    $index,
                    $result->getBody()
                ));
            }
            $response->write(sprintf(
                "Total response time: %dms\n",
                StatelessStopwatch::stop($startTime)
            ));
            $response->end();
        },
        function (\Throwable $error) use ($response) {
            error_log(sprintf('Request failed: %s', $error->getMessage()));
            $response->writeHead(500);
            $response->end();
        }
    )->otherwise(function (\Exception $exception) use ($response) {
        error_log($exception->getMessage());
        $response->writeHead(500);
        $response->end();
    });
};

$socket = new SocketServer('0.0.0.0:80', $eventLoop);
$http = new HttpServer($socket, $eventLoop);

$http->on('request', $app);

$http->on('error', function (\Throwable $error) {
    error_log('Error: ' . $error->getMessage());
});

$eventLoop->run();
