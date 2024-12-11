<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * View Users
     */
    public function index()
    {
        $users = User::all();
        $usersData = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'role' => $user->role->name,
                'permissions' => $user->role->permissions->pluck('name'),
            ];
        });
        return response()->json([
            'usersData' => $usersData
        ]);
    }

    /**
     * Create User
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed!',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the user without the role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);

        if ($request->role_id == 1) {
            $user->assignRole('Admin');
        } elseif ($request->role_id == 2) {
            return response()->json([
                'message' => 'Use Doctor Create Page!',
            ]);
        } elseif ($request->role_id == 3) {
            return response()->json([
                'message' => 'Use Patient Create Page!',
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid Role ID!',
            ]);
        }
        return response()->json([
            'message' => 'User Created Successfully!',
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role->pluck('name')->first()  // Get assigned roles from the user instance
            ],
        ], 201);
    }


    /**
     * 
     * Show User
     */
    public function show(User $user)
    {
        return response()->json([
            "message" => "User's data fetched successfully",
            "data" => $user
        ]);
    }

    /**
     * Update User
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed!',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user->update($request->all());
        return response()->json([
            'message' => 'User Updated Successfully!',
            'data' => $user,
        ], 200);
    }

    /**
     * Delete User
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User Deleted Successfully!',
        ], 200);
    }
}
