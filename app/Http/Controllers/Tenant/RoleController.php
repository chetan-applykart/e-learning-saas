<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
   public function index()
{

    $users = User::whereHas('roles', function($q){
        $q->where('name', '!=', 'user');
    })->where('id', '!=', auth()->id())->get();

    $roles = Role::all();
    $permissions = Permission::all();

    return view('tenant.roles.index', compact('users', 'roles', 'permissions'));
}


    public function updateRolePermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array'
        ]);

        $role = Role::findById($request->role_id);


        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', "Permissions updated globally for Role: {$role->name}");
    }

    public function editAccess(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('tenant.roles.access', compact('user', 'roles', 'permissions'));
    }

    public function updateAccess(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);
        return redirect()->route('tenant.users.index')->with('success', 'User roles updated!');
    }

    public function createRole()
    {
        return view('tenant.roles.create');
    }

    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);

        Role::create(['name' => $request->name, 'guard_name' => 'web']);

        return back()->with('success', 'Role Created Successfully!');
    }

    public function assignByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role'  => 'required|exists:roles,name'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->syncRoles([$request->role]);

        return back()->with('success', "Role assigned to {$request->email} successfully!");
    }
}
