<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return new UserResource($users);
    }

    /**
     * Store a newly created resource in storage.
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
        $user = User::create($request->all());
        return response()->json([
            'message' => 'User Created Successfully!',
            'data' => $user,
        ], 201);
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
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User Deleted Successfully!',
        ], 200);
    }
}
