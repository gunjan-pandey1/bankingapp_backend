<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repository\ForgetPasswordEmailRepository;
use App\Common\EncryptionHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ForgetPasswordService
{
    protected string $queueName;

    public function __construct(
        protected ForgetPasswordEmailRepository $forgetPasswordEmailRepository,
        protected EncryptionHelper $encryptionHelper
    ) {
        $this->queueName = config('queue.connections.rabbitmq.queue', 'forgot_password_queue');
    }

    public function forgetPassword(object $objectParams)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        try {
            $email = $objectParams->email;
            $emailSentBo = ["email" => $email];

            // Check if email exists
            $emailExists = $this->forgetPasswordEmailRepository->sendEmailExists($emailSentBo);
            if (!$emailExists) {
                Log::channel('error')->error("[$currentDateTime] Email does not exist: $email");
                return ["message" => "Email does not exist", "status" => "error", "data" => []];
            }

            // Generate a secure token
            $token = $this->encryptionHelper->generateToken(30);

            // Prepare email data
            $emailData = [
                'email' => $email,
                'token' => $token,
            ];

            // Publish to RabbitMQ
            $this->publishToQueue($emailData);

            Log::channel('info')->info("[$currentDateTime] Email queued successfully: " . $email);

            return ["message" => "Reset email queued successfully", "status" => "success", "data" => []];
        } catch (Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error in queuing email: " . $e->getMessage());
            return [
                "message" => "An error occurred while queuing the email",
                "status" => "error",
                "data" => []
            ];
        }
    }

    /**
     * Publishes a message to the RabbitMQ queue.
     *
     * @param array $data
     * @return void
     */
    private function publishToQueue(array $data)
    {
        try {
            $host = config('queue.connections.rabbitmq.host');
            $port = config('queue.connections.rabbitmq.port');
            $user = config('queue.connections.rabbitmq.user');
            $password = config('queue.connections.rabbitmq.password');

            $connection = new AMQPStreamConnection($host, $port, $user, $password);
            $channel = $connection->channel();

            $channel->queue_declare($this->queueName, false, true, false, false);

            $messageBody = json_encode($data);
            $message = new AMQPMessage(
                $messageBody,
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT] // Make the message persistent
            );

            $channel->basic_publish($message, '', $this->queueName);

            $channel->close();
            $connection->close();
        } catch (Exception $e) {
            Log::channel('error')->error("Error publishing message to queue: " . $e->getMessage());
            throw $e;
        }
    }
}
