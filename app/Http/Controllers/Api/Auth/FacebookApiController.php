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
class FacebookApiController extends Controller
{
    /**
     * Facebook Login
     *
     * Authenticate a user using a Facebook Access Token. Exchanges the provider token for a Sanctum API token. Handles scenarios where the user's email is null or hidden by Facebook privacy settings.
     */
    public function loginWithFacebook(Request $request)
    {
        // 1. Validate the input
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Please provide a valid Facebook access token.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 2. Fetch user data from Facebook
            $facebookUser = Socialite::driver('facebook')->stateless()->userFromToken($request->access_token);

            // Check for the provider ID (Email is no longer strictly required)
            if (!$facebookUser || !$facebookUser->getId()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to retrieve your Facebook account data.'
                ], 401);
            }

            // 3. Process data inside a database transaction
            $result = DB::transaction(function () use ($facebookUser) {

                $email = $facebookUser->getEmail(); // Can be string or null

                // Search first using the Facebook ID
                $existingUser = User::where('provider_type', 'facebook')
                    ->where('provider_id', $facebookUser->getId())
                    ->first();

                // If not found by ID, but Facebook provided an email, search by email
                if (!$existingUser && $email) {
                    $existingUser = User::where('email', $email)->first();
                }

                if ($existingUser) {
                    // ==========================================
                    // SECURITY CHECK 1: Ensure the user is active
                    // ==========================================
                    if (!$existingUser->is_active) {
                        return [
                            'error' => true,
                            'type'  => 'FORBIDDEN',
                            'msg'   => 'عذراً، تم إيقاف حسابك مؤقتاً. يرجى التواصل مع الإدارة.'
                        ];
                    }

                    // SECURITY CHECK 2: Ensure the user's role is strictly 'user'
                    if ($existingUser->role !== 'user') {
                        return [
                            'error' => true,
                            'type'  => 'FORBIDDEN',
                            'msg'   => 'Access denied. This application is restricted to standard users only.'
                        ];
                    }

                    // Check for provider conflicts (e.g., registered via Google or Apple)
                    if ($existingUser->provider_type !== null && $existingUser->provider_type !== 'facebook') {
                        return [
                            'error' => true,
                            'type'  => 'CONFLICT',
                            'msg'   => "This email is already associated with a {$existingUser->provider_type} account. Please log in using {$existingUser->provider_type}."
                        ];
                    }

                    // Update user data (Update email if it was previously null and is now available)
                    $existingUser->update([
                        'provider_id'   => $facebookUser->getId(),
                        'provider_type' => 'facebook',
                        'avatar'        => $facebookUser->getAvatar(),
                        'email'         => $existingUser->email ?? $email,
                    ]);

                    return ['error' => false, 'user' => $existingUser];
                }

                // Create a completely new user (Defaults to active)
                $newUser = User::create([
                    'name'              => $facebookUser->getName() ?? 'Facebook User',
                    'email'             => $email,
                    'avatar'            => $facebookUser->getAvatar(),
                    'provider_id'       => $facebookUser->getId(),
                    'provider_type'     => 'facebook',
                    'role'              => 'user', // Forced to be 'user'
                    'email_verified_at' => $email ? now() : null, // Verify only if an email is present
                ]);

                return ['error' => false, 'user' => $newUser];
            });

            // 4. Check for logical conflict or forbidden errors from Transaction
            if ($result['error']) {
                $statusCode = $result['type'] === 'FORBIDDEN' ? 403 : 409;
                return response()->json([
                    'status'  => false,
                    'message' => $result['msg']
                ], $statusCode);
            }

            // 5. Success - Generate Token and Format Response
            $user = $result['user'];
            $token = $user->createToken('mobile-fb-auth-token')->plainTextToken;

            // Format the photo_url
            $photoUrl = $user->photo ? asset('storage/' . $user->photo) : null;

            return response()->json([
                'status'  => true,
                'message' => 'Successfully logged in via Facebook.',
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
                'message' => 'Invalid or expired Facebook session. Please try logging in again.',
            ], 401);
        } catch (\Throwable $e) {
            Log::error('API Facebook Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An unexpected error occurred while connecting to Facebook. Please try again later.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
