<?php

namespace App\Service;

use Carbon\Carbon;
use App\Constants\CommonConstant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repository\RegisterRepository;


class RegisterService
{
    public function __construct(protected RegisterRepository $registerRepository)
    {}
    public function register(object $registerParams)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        try { 
            $registerGetBo = [
                "email" => $registerParams->email,
                "id" => $registerParams->id ?? null,
            ];
            Log::channel('info')->info("[$currentDateTime]: DB response from registerGet: " . json_encode($registerGetBo));
            $registerDbResponse = $this->registerRepository->registerGet($registerGetBo);
            if ($registerDbResponse) {
                Log::channel('error')->error("[$currentDateTime]: registerDbResponse: " . json_encode($registerDbResponse));
                return ['status' => CommonConstant::ERROR, 'message' => 'Email is already registered', "data" => []];
            }

            $registerInsertBo = [
                "name" => $registerParams->name,
                "email" => $registerParams->email,
                "password" => $registerParams->password,
            ];
            Log::channel('info')->info("Data to be inserted: " . json_encode($registerInsertBo));
            $registerDbResponse = $this->registerRepository->registerCreate($registerInsertBo);
            Log::channel('info')->info("[$currentDateTime] User ID: $registerParams->id - user registration started", ['registerParams' => $registerDbResponse]);
    
            if ($registerDbResponse) {
                Log::channel('info')->info("[$currentDateTime] User ID: $registerParams->id - user data inserted");
                return [
                    "message" => "Register Successful",
                    "status" => "success",
                    "data" => $registerDbResponse["data"] ?? []
                ];
            } else {
                Log::channel('error')->error("[$currentDateTime] User ID: $registerParams->id - user data not inserted");
                return [
                    "message" => "Register Failed",
                    "status" => CommonConstant::FAILED_MESSAGE,
                    "data" => []
                ];    
            }
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error in RegisterService: ".$e->getMessage(), ['exception' => $e]);
            return [
                "message" => "An error occurred while registering user",
                "status" => "error",
                "data" => []
            ];
        }
    }
    
}
