<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * View Roles
     */
    public function index()
    {
        // Fetch roles for sanctum guard
        $roles = Role::all();

        return response()->json($roles);
    }

    /**
     * Create Role
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed!',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the role with the sanctum guard
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'sanctum',
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role,
        ], 201);
    }

    /**
     * Show Role
     */
    public function show(string $id)
    {
        // Fetch the specific role for the sanctum guard
        $role = Role::where('id', $id)->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($role);
    }

    /**
     * Update Role.
     */
    public function update(Request $request, string $id)
    {
        // Validate the update request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . '|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed!',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the role for sanctum guard
        $role = Role::where('id', $id)->where('guard_name', 'sanctum')->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role,
        ]);
    }

    /**
     * Delete Role.
     */
    public function destroy(string $id)
    {
        // Delete the role for sanctum guard
        $role = Role::where('id', $id)->where('guard_name', 'sanctum')->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
