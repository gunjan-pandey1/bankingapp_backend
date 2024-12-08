<?php

namespace App\Helpers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Illuminate\Support\Facades\Log;
use Exception;

class RabbitMQHelper
{
    private ?AMQPStreamConnection $connection = null;
    private ?object $channel = null;
    private array $config;
    
    public function __construct()
    {
        $this->config = [
            'host' => config('queue.connections.rabbitmq.host', 'localhost'),
            'port' => config('queue.connections.rabbitmq.port', 5672),
            'user' => config('queue.connections.rabbitmq.user', 'guest'),
            'password' => config('queue.connections.rabbitmq.password', 'guest'),
            'vhost' => config('queue.connections.rabbitmq.vhost', '/'),
        ];
    }

    /**
     * Create RabbitMQ Connection
     *
     * @return void
     * @throws Exception
     */
    public function connect(): void
    {
        try {
            if (!$this->connection || !$this->connection->isConnected()) {
                $this->connection = new AMQPStreamConnection(
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['user'],
                    $this->config['password'],
                    $this->config['vhost']
                );
                
                $this->channel = $this->connection->channel();
            }
        } catch (Exception $e) {
            Log::error('RabbitMQ Connection Error: ' . $e->getMessage());
            throw new Exception('Failed to connect to RabbitMQ: ' . $e->getMessage());
        }
    }

    /**
     * Publish message to queue
     *
     * @param string $queue
     * @param mixed $message
     * @param array $properties
     * @return bool
     */
    public function publish(string $queue, mixed $message, array $properties = []): bool
    {
        try {
            $this->connect();

            // Declare queue
            $this->channel->queue_declare(
                $queue,
                false,
                true,  // durable
                false,
                false
            );

            // Prepare message properties
            $defaultProperties = [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'application/json'
            ];
            
            $messageProperties = array_merge($defaultProperties, $properties);
            
            // Create and publish message
            $message = new AMQPMessage(
                json_encode($message),
                $messageProperties
            );

            $this->channel->basic_publish($message, '', $queue);
            
            return true;
        } catch (Exception $e) {
            Log::error('RabbitMQ Publishing Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Consume messages from queue
     *
     * @param string $queue
     * @param callable $callback
     * @param array $options
     * @return void
     */
    public function consume(string $queue, callable $callback, array $options = []): void
    {
        try {
            $this->connect();

            // Default options
            $defaultOptions = [
                'prefetch_count' => 1,
                'no_ack' => false,
                'exclusive' => false,
            ];

            $consumeOptions = array_merge($defaultOptions, $options);

            // Set QoS
            $this->channel->basic_qos(
                null,
                $consumeOptions['prefetch_count'],
                null
            );

            // Declare queue
            $this->channel->queue_declare(
                $queue,
                false,
                true,  // durable
                false,
                false
            );

            // Consume messages
            $this->channel->basic_consume(
                $queue,
                '',
                $consumeOptions['exclusive'],
                $consumeOptions['no_ack'],
                false,
                false,
                function ($message) use ($callback) {
                    try {
                        $payload = json_decode($message->getBody(), true);
                        $result = call_user_func($callback, $payload);
                        
                        if ($result !== false) {
                            $message->ack();
                        } else {
                            $message->nack(true); // requeue message
                        }
                    } catch (Exception $e) {
                        Log::error('RabbitMQ Message Processing Error: ' . $e->getMessage());
                        $message->nack(true); // requeue message on error
                    }
                }
            );

            while ($this->channel && $this->channel->is_consuming()) {
                try {
                    $this->channel->wait(null, false, 30);
                } catch (AMQPTimeoutException $e) {
                    // Timeout is normal, continue consuming
                    continue;
                }
            }
        } catch (Exception $e) {
            Log::error('RabbitMQ Consuming Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Close RabbitMQ Connection
     *
     * @return void
     */
    public function close(): void
    {
        try {
            if ($this->channel) {
                $this->channel->close();
            }
            if ($this->connection) {
                $this->connection->close();
            }
        } catch (Exception $e) {
            Log::error('RabbitMQ Close Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Destructor to ensure connections are closed
     */
    public function __destruct()
    {
        $this->close();
    }
}