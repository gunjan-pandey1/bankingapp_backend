<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use App\Common\RedisHelper;
use App\Mail\ForgotpassMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Repository\ForgetPasswordEmailRepository;

class ForgetPasswordService {

    public function __construct(
        protected ForgetPasswordEmailRepository $forgetPasswordEmailRepository,
        protected RedisHelper $redisHelper
    ) {}

    public function forgetPassword(object $objectParams) {
        try {
            $email = $objectParams->email;
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

            $emailSentBo = ["email" => $email];

            // Check if email exists
            $emailsentResponse = $this->forgetPasswordEmailRepository->sendEmailExists($emailSentBo);
            if (!$emailsentResponse) {
                Log::channel('error')->error("[$currentDateTime] Email does not exist");
                return ["message" => "Email does not exist", "status" => "error", "data" => []];
            }

            $token = bin2hex(random_bytes(30)); // A more secure token

            $this->redisHelper->set($token, $email, 300);

            // Log and send email with reset link
            Log::info("[$currentDateTime] Email sent successfully: " . $email);
            Mail::to($email)->send(new ForgotpassMail($token));

            return ["message" => "Email sent successfully", "status" => "success", "data" => []];

        } catch (Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error in sending email: " . $e->getMessage());
            return [
                "message" => "An error occurred while sending email",
                "status" => "error",
                "data" => []
            ];
        }
    }
}
