<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController as BaseController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends BaseController
{
    /**
     * User Login
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

        // Check if user exists and if the password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Fetch the user's role
        $role = $user->role->name;
        // Create a personal access token
        $token = $user->createToken('DocBook')->plainTextToken;

        // Generate and store a remember token
        $rememberToken = Str::random(60);
        $user->remember_token = $rememberToken;
        $user->save();

        // Set attributes to pass to the resource
        $user->setAttribute('token', $token);
        $user->setAttribute('role', $role);
        $user->setAttribute('remember_token', $rememberToken);

        // Return the user resource
        return new UserResource($user);
    }


    /**
     * User Registration
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $input = $request->only(['name', 'email', 'password', 'role_id']);
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Generate and store a remember token
        $rememberToken = Str::random(60);
        $user->remember_token = $rememberToken;
        $user->save();

        $token = $user->createToken('DocBook')->plainTextToken;

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
            'message' => 'User registered successfully.',
        ], 201);
    }


    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        // Get the currently authenticated user
        $userId = Auth::user();
        $id = $userId->id;
        $user = User::find($id);

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized. Please log in to continue.'
            ], 401); // Unauthorized status code
        }

        // Check if the current password matches
        if (Hash::check($request->current_password, $user->password)) {
            // Update the password
            $user->password = Hash::make($request->password); // Encrypt the new password
            $user->save(); // Save the changes

            return response()->json([
                "message" => "Password Updated Successfully"
            ]);
        } else {
            return response()->json([
                "message" => "Current Password doesn't match"
            ], 400); // Bad Request status code
        }
    }


    /**
     * User Logout
     */
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
