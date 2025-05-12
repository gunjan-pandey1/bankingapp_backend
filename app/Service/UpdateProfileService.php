<?php

namespace App\Service;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use App\Repository\UpdateProfileRepository;
use Illuminate\Validation\ValidationException;

class UpdateProfileService
{
    protected $updateProfileRepository;

    public function __construct(UpdateProfileRepository $updateProfileRepository)
    {
        $this->updateProfileRepository = $updateProfileRepository;
    }

    public function updateProfile($request): array
    {
        try {
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $userId = Redis::get('user_id');
            Log::channel('info')->info("UpdateProfileService: User ID from redis: " . $userId);

            // Fetch the current user from DB
            $user = $this->updateProfileRepository->findUserById($userId);

            if (!$user) {
                return [
                    "message" => "User not found",
                    "status" => "error",
                    "data" => []
                ];
            }

            // Check if the provided name, email, and image are the same as the existing data
            if ($user->name === $request->name && $user->email === $request->email && !$request->hasFile('image')) {
                return [
                    "message" => "No changes detected",
                    "status" => "error",
                    "data" => []
                ];
            }

            // Prepare user profile data for update
            $userProfileData = [
                "name" => $request->name,
                "email" => $request->email,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = time() . '_' . $imageFile->getClientOriginalName(); // Store with timestamp
                $imagePath = Storage::disk('public')->put('profile_images', $imageFile);

                // Update image column only if it is null
                if ($user->image === null) {
                    $userProfileData['image'] = $imageName;
                }
            }

            // Update user profile
            $userProfileDbResponse = $this->updateProfileRepository->updateUserProfile($userId, $userProfileData);

            if ($userProfileDbResponse) {
                Log::channel('info')->info("[$currentDateTime] User ID: $userId - User profile updated successfully");
                return [
                    "message" => "User profile updated successfully",
                    "status" => "success",
                    "data" => $userProfileDbResponse
                ];
            } else {
                Log::channel('error')->error("[$currentDateTime] User ID: $userId - Failed to update user profile");
                return [
                    "message" => "Failed to update user profile",
                    "status" => "error",
                    "data" => []
                ];
            }
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error in UpdateProfileService: " . $e->getMessage(), ['exception' => $e]);
            return [
                "message" => "An error occurred while updating profile",
                "status" => "error",
                "data" => []
            ];
        }
    }
}
