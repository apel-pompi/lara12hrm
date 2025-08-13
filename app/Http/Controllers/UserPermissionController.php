<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('id', 'desc')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->roles->map(fn($role) => ['name' => $role->name]),
            ];
        });
        $roles = Role::orderBy('id', 'desc')->get();

        return Inertia::render('allpages/userpermission', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'permissions' => 'required',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        $role = Role::whereIn('id', $validated['permissions'])->get(['id'])->pluck('id');
        $user->assignRole($role);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'success' => true,
            'users' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->roles->pluck('id'), // send role IDs for form binding
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|confirmed',
            'permissions' => 'required|array',
        ]);

        $user = User::findOrFail($id);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        // Assign new roles
        $roles = Role::whereIn('id', $validated['permissions'])->pluck('name')->toArray();
        $user->syncRoles($roles);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
