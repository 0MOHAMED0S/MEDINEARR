<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @tags Authentication
 */
class SocialLogoutController extends Controller
{
    /**
     * Logout Current Device
     *
     * Securely log the user out by revoking the access token for their current active session.
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user || !$user->currentAccessToken()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated or invalid session.',
                ], 401);
            }
            $user->currentAccessToken()->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Successfully logged out. Your session has been securely closed.'
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Logout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while logging out. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Logout All Devices
     *
     * Revoke all active access tokens across all devices for the authenticated user. This forces the user to log in again everywhere.
     */
    public function logoutAllDevices(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated or invalid session.',
                ], 401);
            }
            $user->tokens()->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Successfully logged out from all devices. All active sessions have been securely closed.'
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Logout All Devices Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while attempting to log out from all devices. Please try again later.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
