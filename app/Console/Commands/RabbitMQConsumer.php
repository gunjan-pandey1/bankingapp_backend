<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\RabbitMQService;

class RabbitMQConsumer extends Command
{
    protected $signature = 'rabbitmq:consume';
    protected $description = 'Consume messages from RabbitMQ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(RabbitMQService $rabbitMQService)
    {
        $queue = env('RABBITMQ_QUEUE', 'default_queue');

        $rabbitMQService->consumeMessages($queue, function ($message) {
            $this->info("Received message: " . $message);
        });

        return 0;
    }
}
