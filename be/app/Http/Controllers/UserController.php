<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user (guests and admins)",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "phone", "password"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+421912345678"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 enum={"guest", "admin"},
     *                 example="admin",
     *                 description="Optional. Only admins can set this. Ignored for guests."
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered"),
     *     @OA\Response(response=403, description="Only admins can register admin users"),
     *     @OA\Response(response=422, description="Validation error"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function registerUser(Request $request) {
        $authUser = auth()->user();

        if ($authUser && $authUser->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone',
                'regex:/^\+42[01][0-9]{9}$/'
            ],
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|in:guest,admin',
        ]);

        $role = $request->input('role', 'guest');
        if (!$authUser || $authUser->role !== 'admin') {
            $role = 'guest';
        }

        User::create([
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'password' => isset($validated['password']) ? bcrypt($validated['password']) : null,
            'role' => $role,
        ]);

        return response()->json(['message' => 'User registered'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users (admin only)",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of all users"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function getAllUsers() {
        $authUser = auth()->user();

        if (!$authUser || $authUser->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(User::all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/names",
     *     summary="Get guest user names for selection (admin only)",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of user names"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function getUserNames() {
        $authUser = auth()->user();

        if (!$authUser || $authUser->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $users = User::where('role', 'guest')
            ->select('id', 'first_name', 'last_name')
            ->get();

        return response()->json(
            $users->map(fn ($user) => [
                'id' => $user->id,
                'name' => trim("{$user->first_name} {$user->last_name}") ?: $user->last_name,
            ])
        );
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+421912345678"),
     *             @OA\Property(property="password", type="string", example="newpassword"),
     *             @OA\Property(property="role", type="string", enum={"guest", "admin"}, example="admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated"),
     *     @OA\Response(response=403, description="Access denied"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateUser(Request $request, User $user) {
        $authUser = auth()->user();

        if (!$authUser || ($authUser->role !== 'admin' && $authUser->id !== $user->id)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $rules = [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+42[01][0-9]{9}$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:6',
        ];

        if ($authUser->role === 'admin') {
            $rules['role'] = ['nullable', Rule::in(['guest', 'admin'])];
        }

        $validated = $request->validate($rules);

        $user->update([
            'first_name' => $validated['first_name'] ?? $user->first_name,
            'last_name' => $validated['last_name'] ?? $user->last_name,
            'email' => $validated['email'] ?? $user->email,
            // 'phone' => $validated['phone'] ?? $user->phone,
            'password' => isset($validated['password']) ? bcrypt($validated['password']) : $user->password,
            'role' => $validated['role'] ?? $user->role,
        ]);

        return response()->json(['message' => 'User updated']);
    }
}
