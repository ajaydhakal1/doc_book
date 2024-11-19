<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        if ($user) {
            $role = Role::findByName('user');
            $user->assignRole($role);
            return redirect()->route('users.index')->with('success', 'User created successfully');
        }
        return redirect()->back()->with('error', 'Failed to create user');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();

        $hasRoles = $user->roles->pluck('id');
        return view('users.edit', compact('user', 'roles', 'hasRoles'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|exists:roles,id',
        ]);

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;

        // Assign the new role
        $role = Role::find($request->role);
        $user->syncRoles($role);

        // Save the updated user
        $user->save();

        // Redirect to the appropriate page based on the role
        if ($role->name == 'patient') {
            return redirect()->route('patients.index')->with('success', 'User role updated to Patient.');
        }

        // Redirect to the user index if not a patient
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if ($user) {
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }
}
