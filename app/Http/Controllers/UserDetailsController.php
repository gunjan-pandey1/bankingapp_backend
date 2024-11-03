<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;

class UserDetailsController extends Controller
{
    // Method to get all users
    public function getAllUsers()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching users: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method to delete a user
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            // Delete the user
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the user: ' . $e->getMessage()
            ], 500);
        }
    }
}
