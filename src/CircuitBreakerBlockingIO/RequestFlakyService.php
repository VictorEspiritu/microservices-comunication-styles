<?php
declare(strict_types = 1);

namespace CircuitBreakerBlockingIO;

use Odesk\Phystrix\AbstractCommand;

final class RequestFlakyService extends AbstractCommand
{
    protected function run()
    {
        $response = $this->httpClient()->get(
            'http://flaky_service/',
            [
                'timeout' => 0.5
            ]
        );

        return $response->getBody();
    }

    protected function getFallback()
    {
        return 'Fallback response';
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function httpClient()
    {
        return $this->serviceLocator->get('http_client');
    }

    protected function processExecutionEvent($eventName)
    {
        error_log("Circuit breaker event: " . $eventName);
    }
}
