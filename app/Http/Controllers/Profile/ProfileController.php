<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show(Request $request, ?int $id = null)
    {
        if (!$id) {
            $id = Auth::id(); 
            
            if (!$id) {
                return redirect()->route('login');
            }
        }
        
        // Get profile for the user
        $profile = Profile::where('user_id', $id)->first();
        
        // Return Inertia response (profile can be null, frontend will handle it)
        return Inertia::render('profile/index', [
            'profile' => $profile,
        ]);
    } 
    public function create(Request $request)
    {
        
        $existingProfile = Profile::where('user_id', Auth::id())->first();
        
        if ($existingProfile) {
            return response()->json([
                'message' => 'Profile already exists for this user'
            ], 409); 
        }

        $credentials = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['nullable', 'date'],
        ]);

        
        $credentials['user_id'] = Auth::id();
    
        $profile = Profile::create($credentials);

        // Check if request expects JSON (API) or redirect (Web)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Profile created successfully!',
                'data' => $profile
            ], 201);
        }

        return Redirect::to('/profile')->with('success', 'Profile created successfully!');
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

        // Check if request expects JSON (API) or redirect (Web)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Profile updated successfully!',
                'data' => $profile
            ], 200);
        }

        return Redirect::to('/profile')->with('success', 'Profile updated successfully!');
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