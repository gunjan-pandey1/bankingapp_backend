<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\RabbitMQService;

class RabbitMQController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function sendMessage()
    {
        $message = 'Hello from Laravel!';
        $queue = env('RABBITMQ_QUEUE', 'default_queue');

        $this->rabbitMQService->publishMessage($queue, $message);

        return response()->json(['message' => 'Message sent to RabbitMQ']);
    }
}
