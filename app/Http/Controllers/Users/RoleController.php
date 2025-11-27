<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $this->authorize('role.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $roles = Role::orderBy('id', 'desc')->get();
        $rolesWithPermissions = $roles->map(function ($role) {
        $grouped = DB::table('role_has_permissions as rp')
                ->leftJoin('permissions', 'rp.permission_id', '=', 'permissions.id')
                ->where('rp.role_id', $role->id)
                ->select('permissions.group_name', 'permissions.name')
                ->get()
                ->groupBy('group_name')
                ->map(function ($group) {
                    return $group->pluck('name')->values();
                });

            return [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $grouped,
            ];
        });
        
        $permissionGroups = Permission::groupBy('group_name')
            ->select('group_name')
            ->get();
        $permissionsByGroup = [];
        foreach ($permissionGroups as $group) {
            $permissionsByGroup[$group->group_name] = User::getPermissions($group->group_name);
        }

        return Inertia::render('allpages/user/roles',[
            'roles' => $rolesWithPermissions,
            'permissionGroups' => $permissionGroups,
            'permissionsByGroup' => $permissionsByGroup
        ]);

    }
    
    public function store(Request $request){

        try {
            $this->authorize('role.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'name' => 'required',
        ]);
        $role = Role::create(['name'=>$validated['name']]);
        $permission = Permission::whereIn('id',$request->permissionid)->get(['id'])->pluck('id');
        
        if (!empty($permission)) {
            $role->syncPermissions($permission);
        }
    }

    public function edit(string $id)
    {
        try {
            $this->authorize('role.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        
        $role = Role::with('permissions')->findOrFail($id);
        $permissionGroup = Permission::groupBy('group_name')->select('group_name')->get();
        $permissions = Permission::all();
        return response()->json([
            'success' => true,
            'roles' => $role,
            'permissionGroup' => $permissionGroup,
            'permissions' => $permissions,
            'assignedPermissions' => $role->permissions->pluck('id'), // this is key
        ]);
    }

    public function update(Request $request, string $id){

        try {
            $this->authorize('role.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'name' => 'required',
        ]);
        $role = Role::find($id);
        $permission = Permission::whereIn('id',$request->permissionid)->get(['id'])->pluck('id');
        
        if (!empty($permission)) {
            $role->name = $validated['name'];
            $role->save();
            $role->syncPermissions($permission);
        }
        return redirect()->route('roles.index')->with('success', 'Roles Update successfully.');
    }

    public function destroy(string $id)
    {
        try {
            $this->authorize('role.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $role = Role::find($id);
        if (!is_null($role)) {
            $role->delete();
        }
        
    }
}
