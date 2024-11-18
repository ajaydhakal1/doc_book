<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class PermissionController extends Controller
{

    // public static function middleware()
    // {
    //     return [
    //         new Middleware('permission:view permissions', only: ['index']),
    //         new Middleware('permission:edit permissions', only: ['edit']),
    //         new Middleware('permission:create permissions', only: ['create']),
    //         new Middleware('permission:delete permissions', only: ['destroy']),
    //     ];
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);

        // If validation fails, redirect back with input and error messages
        if ($validator->fails()) {
            return redirect()
                ->route('permissions.create')
                ->withInput()
                ->withErrors($validator);
        }

        // Create the permission if validation passes
        Permission::create(['name' => $request->name]);

        // Redirect to the index page with a success message
        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // This can be implemented if required, for now returning 404
        abort(404, 'Not implemented');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the permission by ID or fail with a 404 response
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the permission by ID or fail with a 404 response
        $permission = Permission::findOrFail($id);

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,' . $id . ',id',
        ]);

        // If validation fails, redirect back with input and error messages
        if ($validator->fails()) {
            return redirect()
                ->route('permissions.edit', ['permission' => $id])
                ->withInput()
                ->withErrors($validator);
        }

        // Update the permission if validation passes
        $permission->update(['name' => $request->name]);

        // Redirect to the index page with a success message
        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the permission by ID or fail with a 404 response
        $permission = Permission::findOrFail($id);

        // Delete the permission
        $permission->delete();

        // Redirect to the index page with a success message
        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
