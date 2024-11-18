<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class RoleController extends Controller
{

    // public static function middleware()
    // {
    //     return [
    //         new Middleware('permission:view roles', only: ['index']),
    //         new Middleware('permission:edit roles', only: ['edit']),
    //         new Middleware('permission:create roles', only: ['create']),
    //         new Middleware('permission:delete roles', only: ['destroy']),
    //     ];
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('created_at', 'DESC')->paginate(5);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3',
            'permission' => 'nullable|array', // Ensure permission is an array if present
            'permission.*' => 'exists:permissions,name', // Validate that each permission exists
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::create(['name' => $request->name]);

        // Assign permissions if provided
        if (!empty($request->permission)) {
            $role->syncPermissions($request->permission);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404, 'Not implemented');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name'); // Get the role's permissions
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.edit', compact('permissions', 'hasPermissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . ',id',
            'permission' => 'nullable|array', // Ensure permission is an array if present
            'permission.*' => 'exists:permissions,name', // Validate that each permission exists
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role->update(['name' => $request->name]);

        // Sync permissions if provided
        $role->syncPermissions($request->permission ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
