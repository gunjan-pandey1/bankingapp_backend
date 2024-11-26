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
        try { 
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            // Prepare data to check if email already exists
            $registerGetBo = [
                "email" => $registerParams->email,
                "id" => $registerParams->id,
            ];
            // Get response from repository
            $registerDbResponse = $this->registerRepository->registerGet($registerGetBo);
            if ($registerDbResponse) {
                Log::channel('error')->error(message: "[$currentDateTime]: registerDbResponse: " . json_encode($registerDbResponse));
                return ['status' => CommonConstant::ERROR, 'message' => 'Email is already registered',  "data" => []];
            }
            Log::channel('info')->info("[$currentDateTime]: DB response from registerGet: " . json_encode($registerGetBo));

            // Prepare data for insertion
            $registerInsertBo = [
                "name" => $registerParams->name,
                "email" => $registerParams->email,
                "password" => Hash::make($registerParams->password),
                // "password" => Hash::make($registerParams->password),
            ];

            // Log the insertion data for debugging
            Log::info("Data to be inserted: " . json_encode($registerInsertBo));

            // Insert the user into the database
            $registerDbResponse = $this->registerRepository->registerCreate($registerInsertBo);

            // Log the DB response after insertion
            // Log::info("DB response from registerCreate: " . json_encode($registerDbResponse));
            
            // if ($registerDbResponse) {
            //     Log::info("DB response from registerCreate success: " . json_encode($registerDbResponse));
            //     return [
            //         "message" => "Register Successful",
            //         "status" => "success",
            //         "data" => $registerDbResponse["data"] ?? []
            //     ];
            // } else {
            //     Log::info("DB response from registerCreate error: " . json_encode($registerDbResponse));
            //     return [
            //         "message" => "Register Failed",
            //         "status" => "fail",
            //         "data" => []
            //     ];        
            // }/
            Log::channel('info')->info("[$currentDateTime] User ID: $registerParams->id - user registration started", ['registerParams'=> $registerDbResponse]);
            if ($registerDbResponse) {
                Log::channel('info')->info("[$currentDateTime] User ID: $registerParams->id - user data inserted");
            } else {
                Log::channel('error')->error("[$currentDateTime] User ID: $registerParams->id - user data not inserted");
                return ['status' => CommonConstant::FAILED_MESSAGE, 'message' => 'Something went wrong'];
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::channel('error')->error("[$currentDateTime] Error in RegisterService: ".$e->getMessage());
            return [
                "message" => "An error occurred while registering user",
                "status" => "error",
                "data" => []
            ];
        }
    }
}
