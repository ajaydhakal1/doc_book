<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * View Permissions
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * Create Permission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);

        // Create the permission if validation passes
        Permission::create(['name' => $request->name]);
        return response()->json(['message' => 'Permission created successfully'], 201);
    }

    /**
     * Show Permission
     */
    public function show(Permission $permission)
    {
        return response()->json([
            "Permission" => $permission,
        ]);
    }

    /**
     * Update Permission
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->all());
        return response()->json([
            'message' => 'Permission Updated Successfully'
        ]);
    }

    /**
     * Delete Permission
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json([
            'message' => 'Permission deleted successfully'
        ]);
    }
}
