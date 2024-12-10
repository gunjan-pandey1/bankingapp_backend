<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use App\Mail\ForgotpassMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Repository\ForgetPasswordEmailRepository;
use App\Common\EncryptionHelper;

class ForgetPasswordService {

    public function __construct(
        protected ForgetPasswordEmailRepository $forgetPasswordEmailRepository,
        protected EncryptionHelper $encryptionHelper
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

             // A more secure token
            $token = $this->encryptionHelper->generateToken(30);

            // $this->redisHelper->set($token, $email, 300);

            // Log and send email with reset link
            Log::channel('info')->info("[$currentDateTime] Email sent successfully: " . $email);
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
