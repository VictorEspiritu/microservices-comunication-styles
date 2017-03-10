<?php

use Bunny\Client;

require __DIR__ . '/../../vendor/autoload.php';

$connection = [
    'host' => 'rabbitmq',
    'vhost' => '/',
    'user' => 'guest',
    'password' => 'guest'
];

$client = new Client($connection);
$client->connect();

return $client;
