<?php

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;

/** @var Client $client */
$client = require __DIR__ . '/bootstrap.php';

$channel = $client->channel();
$channel->exchangeDeclare('events', 'topic');
$channel->queueDeclare('all_events');
$channel->queueBind('all_events', 'events');

$channel->run(
    function (Message $message, Channel $channel) {
        echo 'Handling message: ' . $message->content . "\n";

        $channel->ack($message); // Acknowledge message
    },
    'all_events'
);
