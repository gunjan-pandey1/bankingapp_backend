<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQForgotPasswordConsumer extends Command
{
    protected $signature = 'rabbitmq:consume-forgot-password';
    protected $description = 'Consume forgot password email messages from RabbitMQ';

    public function handle()
    {
        $connection = new AMQPStreamConnection(
            config('queue.connections.rabbitmq.host'),
            config('queue.connections.rabbitmq.port'),
            config('queue.connections.rabbitmq.user'),
            config('queue.connections.rabbitmq.password')
        );
        $channel = $connection->channel();
        $queueName = config('queue.connections.rabbitmq.queue', 'forgot_password_queue');

        $channel->basic_consume(
            $queueName,
            '',
            false,
            true,
            false,
            false,
            function (AMQPMessage $msg) {
                $emailData = json_decode($msg->getBody(), true);
                Log::channel('info')->info("Forgot password email data: " . json_encode($emailData, true));
                Mail::to($emailData['email'])->send(new ForgotPasswordMail($emailData['token']));
                Log::channel('info')->info("Forgot password email sent to: " . $emailData['email']);
            }
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
