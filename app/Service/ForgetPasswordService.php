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
        // Set the queue name from the config file
        $this->queueName = config('queue.connections.rabbitmq.queue');
    }

    public function forgetPassword(object $objectParams)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        try {
            $email = $objectParams->email;
            $emailSentBo = ["email" => $email];

            // Check if email exists
            $emailsentResponse = $this->forgetPasswordEmailRepository->sendEmailExists($emailSentBo);
            if (!$emailsentResponse) {
                Log::channel('error')->error("[$currentDateTime] Email does not exist");
                return ["message" => "Email does not exist", "status" => "error", "data" => []];
            }

            // Generate a secure token
            $token = $this->encryptionHelper->generateToken(30);

            // Publish the email data to RabbitMQ
            $this->publishToQueue([
                'email' => $email,
                'token' => $token,
            ]);

            Log::channel('info')->info("[$currentDateTime] Email queued successfully: " . $email);

            return ["message" => "Email queued successfully", "status" => "success", "data" => []];
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
            $host = config('queue.connections.rabbitmq.hosts.0.host');
            $port = config('queue.connections.rabbitmq.hosts.0.port');
            $user = config('queue.connections.rabbitmq.hosts.0.user');
            $password = config('queue.connections.rabbitmq.hosts.0.password');

            // Establish connection to RabbitMQ
            $connection = new AMQPStreamConnection($host, $port, $user, $password);
            $channel = $connection->channel();

            // Declare the queue
            $channel->queue_declare($this->queueName, false, true, false, false);

            // Create the message with a JSON payload
            $messageBody = json_encode($data);
            $message = new AMQPMessage(
                $messageBody,
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT] // Make the message persistent
            );

            // Publish the message to the queue
            $channel->basic_publish($message, '', $this->queueName);

            // Close the channel and connection
            $channel->close();
            $connection->close();
        } catch (Exception $e) {
            Log::channel('error')->error("Error publishing message to queue: " . $e->getMessage());
            throw $e;
        }
    }
}
