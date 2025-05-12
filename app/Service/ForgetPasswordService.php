<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repository\ForgetPasswordEmailRepository;
use App\Common\EncryptionHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Service\RabbitMQService;

class ForgetPasswordService
{
    protected string $queueName;

    public function __construct(
        protected ForgetPasswordEmailRepository $forgetPasswordEmailRepository,
        protected EncryptionHelper $encryptionHelper,
        protected RabbitMQService $rabbitMQService // Add this line
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
            $this->rabbitMQService->publish($emailData);

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
     
}
