<?php

namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', '127.0.0.1'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );
        $this->channel = $this->connection->channel();
    }

    public function publishMessage($queue, $message)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage($message, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, '', $queue);
    }

    public function consumeMessages($queue, $callback)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $this->channel->basic_consume($queue, '', false, true, false, false, function ($msg) use ($callback) {
            call_user_func($callback, $msg->body);
        });

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
