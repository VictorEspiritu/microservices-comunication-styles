<?php

use Bunny\Client;

/** @var Client $client */
$client = require __DIR__ . '/bootstrap.php';

$channel = $client->channel();
$channel->exchangeDeclare('events', 'topic');

$channel->publish(
    'Test',
    [],
    'events'
);
error_log('Produced a test message');
