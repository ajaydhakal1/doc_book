<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController as BaseController;
use Spatie\Permission\Models\Role;

class AuthController extends BaseController
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Retrieve the user by email
        $user = User::where('email', $validated['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Fetch user's role and permissions
        $roleName = $user->role->name; // Assuming Spatie roles
        $permissions = $user->role->permissions->pluck('name');

        // Create a personal access token
        $token = $user->createToken($roleName ?? 'User')->plainTextToken;

        // Attach additional attributes to the resource
        $user->setAttribute('token', $token);
        $user->setAttribute('roleName', $roleName ?? 'Unknown');
        $user->setAttribute('permissions', $permissions);

        // Return user resource with token
        return new UserResource($user);
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $input = $request->only(['name', 'email', 'password']);
        $input['password'] = bcrypt($input['password']);
        $input['role_id'] = $request->input('role_id');

        $user = User::create($input);

        $token = $user->createToken('MyApp')->plainTextToken;

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'message' => 'User registered successfully.',
        ], 201);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Remove unused methods or implement them if needed
    // public function update(Request $request, string $id) { ... }
    // public function destroy(string $id) { ... }
}
