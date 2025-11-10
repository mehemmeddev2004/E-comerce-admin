<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request, ?int $id = null)
    {
       
        if (!$id) {
            $id = Auth::id(); 
            
            if (!$id) {
                return response()->json([
                    'message' => 'Unauthorized - Please login first'
                ], 401);
            }
        }
        
        // Profile tap
        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }
        
        return response()->json([
            'message' => 'Profile fetched successfully!',
            'data' => $profile
        ], 200);
    } 
    public function create(Request $request)
    {
        // Check if user already has a profile
        $existingProfile = Profile::where('user_id', Auth::id())->first();
        
        if ($existingProfile) {
            return response()->json([
                'message' => 'Profile already exists for this user'
            ], 409); // 409 Conflict
        }

        $credentials = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['nullable', 'date'],
        ]);

        // Automatically use authenticated user's ID
        $credentials['user_id'] = Auth::id();
    
        $profile = Profile::create($credentials);

        return response()->json([
            'message' => 'Profile created successfully!',
            'data' => $profile
        ], 201);
    }


    public function update(Request $request, ?int $id = null)
    {
      
        if (!$id) {
            $id = Auth::id();
        }

        if ($id !== Auth::id()) {
            return response()->json([
                'message' => 'Forbidden - You can only update your own profile'
            ], 403);
        }

        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }

        $credentials = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['nullable', 'date'],
        ]);

        $profile->update($credentials);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'data' => $profile
        ], 200);
    }

    public function delete(Request $request, ?int $id = null)
    {
        
        if (!$id) {
            $id = Auth::id();
        }

       
        if ($id !== Auth::id()) {
            return response()->json([
                'message' => 'Forbidden - You can only delete your own profile'
            ], 403);
        }

        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }

        $profile->delete();

        return response()->json([
            'message' => 'Profile deleted successfully!'
        ], 200);
    }
}