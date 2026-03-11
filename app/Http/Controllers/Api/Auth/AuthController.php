<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UpdateLocationRequest;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @tags Profile
 */
class AuthController extends Controller
{
    /**
     * Get Profile
     *
     * Retrieve the authenticated user's profile details including their photo URL, phone number, location, and verification status.
     */
    public function getProfile(Request $request)
    {
        try {
            $user = $request->user();

            // Case 1: Unauthenticated or missing user data (Edge case fallback)
            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated or invalid session. Please log in again.'
                ], 401);
            }

            // Optional: Format the local photo URL if it exists
            $photoUrl = $user->photo ? asset('storage/' . $user->photo) : null;

            // Case 2: Successfully retrieved profile
            return response()->json([
                'status'  => true,
                'message' => 'User profile retrieved successfully.',
                'data'    => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'phone'          => $user->phone,
                    'photo_url'      => $photoUrl,
                    'avatar'         => $user->avatar,
                    'role'           => $user->role,
                    'provider_type'  => $user->provider_type,
                    'latitude'       => $user->latitude,      // Added location
                    'longitude'      => $user->longitude,     // Added location
                    'email_verified' => !is_null($user->email_verified_at),
                    'joined_at'      => $user->created_at ? $user->created_at->format('Y-m-d') : null,
                ]
            ], 200);
        } catch (\Throwable $e) {
            // Case 3: Unexpected technical error
            Log::error('API Get Profile Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while retrieving your profile. Please try again later.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    /**
     * Update Profile
     *
     * Update the authenticated user's profile information. Allows updating the name, phone number, and uploading a new profile photo (max 2MB).
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated or invalid session. Please log in again.'
                ], 401);
            }

            // 1. Retrieve ONLY the data that passed validation safely
            $validated = $request->validated();

            // 2. Handle Photo Upload
            if ($request->hasFile('photo')) {
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $path = $request->file('photo')->store('users/photos', 'public');
                $user->photo = $path;
            }

            // 3. Update Name and Phone if they exist in the validated payload
            if (isset($validated['name'])) {
                $user->name = $validated['name'];
            }

            // array_key_exists allows the user to intentionally set the phone to null if they want to remove it
            if (array_key_exists('phone', $validated)) {
                $user->phone = $validated['phone'];
            }

            // Save the changes to the database
            $user->save();

            // Format the updated photo URL for the response
            $photoUrl = $user->photo ? asset('storage/' . $user->photo) : null;

            // 4. Return the updated profile data
            return response()->json([
                'status'  => true,
                'message' => 'Profile updated successfully.',
                'data'    => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'phone'          => $user->phone,
                    'photo_url'      => $photoUrl,
                    'avatar'         => $user->avatar,
                    'role'           => $user->role,
                    'provider_type'  => $user->provider_type,
                    'latitude'       => $user->latitude,
                    'longitude'      => $user->longitude,
                    'email_verified' => !is_null($user->email_verified_at),
                    'joined_at'      => $user->created_at ? $user->created_at->format('Y-m-d') : null,
                ]
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Update Profile Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while updating your profile. Please try again later.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    /**
     * Update Location
     *
     * Store or update the authenticated user's geographical coordinates (latitude and longitude).
     */
    public function updateLocation(UpdateLocationRequest $request) // <-- Use the new Request class
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated or invalid session.'
                ], 401);
            }

            // 1. Retrieve ONLY the cleanly validated data
            $validated = $request->validated();

            // 2. Update the user's location
            $user->update([
                'latitude'  => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Location updated successfully.',
                'data'    => [
                    'latitude'  => $user->latitude,
                    'longitude' => $user->longitude,
                ]
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Update Location Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while updating the location. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
