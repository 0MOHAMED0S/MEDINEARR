<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;

/**
 * @tags Authentication
 */
class GoogleApiController extends Controller
{
    /**
     * Google Login
     *
     * Authenticate a user using a Google Access Token. Exchanges the provider token for a Sanctum API token. Handles user creation and provider conflict resolution.
     */
    public function loginWithGoogle(Request $request)
    {
        // 1. Validate the input
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Please provide a valid Google access token.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 2. Fetch user data from Google
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->access_token);

            // Check for the provider ID (Email is no longer strictly required)
            if (!$googleUser || !$googleUser->getId()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to retrieve your Google account data.'
                ], 401);
            }

            // 3. Process data inside a database transaction
            $result = DB::transaction(function () use ($googleUser) {

                $email = $googleUser->getEmail(); // Can be string or null

                // Search first using the Google ID
                $existingUser = User::where('provider_type', 'google')
                    ->where('provider_id', $googleUser->getId())
                    ->first();

                // If not found by ID, but Google provided an email, search by email
                if (!$existingUser && $email) {
                    $existingUser = User::where('email', $email)->first();
                }

                if ($existingUser) {
                    // SECURITY CHECK: Ensure the user's role is strictly 'user'
                    if ($existingUser->role !== 'user') {
                        return [
                            'error' => true,
                            'type'  => 'FORBIDDEN',
                            'msg'   => 'Access denied. This application is restricted to standard users only.'
                        ];
                    }

                    // Check for provider conflicts (e.g., registered via Facebook or Apple)
                    if ($existingUser->provider_type !== null && $existingUser->provider_type !== 'google') {
                        return [
                            'error' => true,
                            'type'  => 'CONFLICT',
                            'msg'   => "This email is already associated with a {$existingUser->provider_type} account. Please log in using {$existingUser->provider_type}."
                        ];
                    }

                    // Update user data
                    $existingUser->update([
                        'provider_id'   => $googleUser->getId(),
                        'provider_type' => 'google',
                        'avatar'        => $googleUser->getAvatar(),
                        'email'         => $existingUser->email ?? $email, // Update email if it was previously null
                    ]);

                    return ['error' => false, 'user' => $existingUser];
                }

                // Create a completely new user
                $newUser = User::create([
                    'name'              => $googleUser->getName() ?? 'Google User',
                    'email'             => $email,
                    'avatar'            => $googleUser->getAvatar(),
                    'provider_id'       => $googleUser->getId(),
                    'provider_type'     => 'google',
                    'role'              => 'user', // Forced to be 'user'
                    'email_verified_at' => $email ? now() : null, // Verify only if an email is present
                ]);

                return ['error' => false, 'user' => $newUser];
            });

            // Check for logical conflict or forbidden errors
            if ($result['error']) {
                $statusCode = $result['type'] === 'FORBIDDEN' ? 403 : 409;
                return response()->json([
                    'status'  => false,
                    'message' => $result['msg']
                ], $statusCode);
            }

            $user = $result['user'];
            $token = $user->createToken('mobile-google-auth-token')->plainTextToken;

            // Format the photo_url
            $photoUrl = $user->photo ? asset('storage/' . $user->photo) : null;

            return response()->json([
                'status'  => true,
                'message' => 'Successfully logged in via Google.',
                'data'    => [
                    'user'       => [
                        'id'             => $user->id,
                        'name'           => $user->name,
                        'email'          => $user->email,
                        'phone'          => $user->phone,
                        'photo_url'      => $photoUrl,
                        'avatar'         => $user->avatar,
                        'role'           => $user->role,
                        'provider_type'  => $user->provider_type,
                        'provider_id'    => $user->provider_id,
                        'latitude'       => $user->latitude,      // Added location
                        'longitude'      => $user->longitude,     // Added location
                        'email_verified' => !is_null($user->email_verified_at),
                        'joined_at'      => $user->created_at ? $user->created_at->format('Y-m-d') : null,
                    ],
                    'token'      => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);
        } catch (ClientException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired Google session. Please try logging in again.',
            ], 401);
        } catch (\Throwable $e) {
            Log::error('API Google Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An unexpected error occurred while connecting to Google. Please try again later.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
