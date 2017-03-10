<?php
declare(strict_types = 1);

namespace CircuitBreakerBlockingIO;

use GuzzleHttp\Client;
use Odesk\Phystrix\AbstractCommand;
use Zend\Config\Config;
use Odesk\Phystrix\ApcStateStorage;
use Odesk\Phystrix\CircuitBreakerFactory;
use Odesk\Phystrix\CommandMetricsFactory;
use Odesk\Phystrix\CommandFactory;
use Zend\Di\ServiceLocator;

final class Application
{
    private $serviceLocator;

    public function __construct()
    {
        $config = new Config([
            'default' => [
                'fallback' => [
                    'enabled' => true,
                ],
                'circuitBreaker' => [
                    'enabled' => true,
                    // How many failed request it might be before we open the circuit (disallow consecutive requests)
                    'errorThresholdPercentage' => 50,
                    // How many requests we need minimally before we can start making decisions about service stability
                    'requestVolumeThreshold' => 1,
                    // For how long to wait before attempting to access a failing service
                    'sleepWindowInMilliseconds' => 3000,
                ],
                'metrics' => [
                    'healthSnapshotIntervalInMilliseconds' => 1000,
                    'rollingStatisticalWindowInMilliseconds' => 1000,
                    'rollingStatisticalWindowBuckets' => 10,
                ]
            ]
        ]);

        $serviceLocator = new ServiceLocator();

        $serviceLocator->set('command_factory', function() use ($serviceLocator, $config) {
            $stateStorage = new ApcStateStorage();
            $circuitBreakerFactory = new CircuitBreakerFactory($stateStorage);
            $commandMetricsFactory = new CommandMetricsFactory($stateStorage);

            return new CommandFactory(
                $config,
                $serviceLocator,
                $circuitBreakerFactory,
                $commandMetricsFactory
            );
        });

        $serviceLocator->set('http_client', function() {
            return new Client();
        });

        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return RequestFlakyService|\Odesk\Phystrix\AbstractCommand
     */
    public function createRequestFlakyServiceCommand() : AbstractCommand
    {
        return $this->commandFactory()->getCommand(RequestFlakyService::class);
    }

    protected function commandFactory(): CommandFactory
    {
        return $this->serviceLocator->get('command_factory');
    }
}
