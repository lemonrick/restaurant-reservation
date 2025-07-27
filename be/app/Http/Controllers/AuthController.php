<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @OA\Info(title="Yummy API", version="1.0")
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 *     description="Use a token returned from the login endpoint",
 * )
 */
class AuthController extends Controller {

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log in a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'invalid_credentials',
                'message' => 'These credentials do not match our records.',
            ], 422);
        }

        $abilities = $user->role === 'admin' ? ['admin'] : [];
        $token = $user->createToken('api-token', $abilities);

        $plainTextToken = $token->plainTextToken;

        $accessToken = PersonalAccessToken::find($token->accessToken->id);
        $accessToken->expires_at = now()->addDays(30);
        $accessToken->save();

        return response()->json([
            'user' => array_merge(
                $user->only(['id', 'first_name', 'last_name', 'email', 'phone', 'role']),
                ['token' => $plainTextToken]
            )
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out the authenticated user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful"
     *     )
     * )
     */
    public function logout(Request $request) {
        // $request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->update(['expires_at' => now()->subMinute()]);
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
