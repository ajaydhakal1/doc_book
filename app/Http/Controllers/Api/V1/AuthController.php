<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
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
        $roleName = Role::where('id', $user->role_id)->pluck('name')->first();
        $permissions = $user->role->permissions->pluck('name');
        // Create a personal access token
        $token = $user->createToken($roleName)->plainTextToken;

        // Set attributes to pass to the resource
        $user->setAttribute('token', $token);
        $user->setAttribute('roleName', $roleName);
        $user->setAttribute('permissions', $permissions);

        // Return the user resource
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
