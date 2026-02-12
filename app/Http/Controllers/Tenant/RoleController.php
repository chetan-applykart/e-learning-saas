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
        $users = User::where('id', '!=', auth()->id())->get();
        return view('tenant.roles.index', compact('users'));
    }

    public function editAccess(User $user)
    {
        if (!auth()->user()->hasRole('tenant-admin') && $user->hasRole('tenant-admin')) {
            return back()->with('error', 'Sirf Super Admin hi Tenant Admin ke permissions badal sakta hai!');
        }

        $roles = Role::whereNotIn('name', ['tenant-admin'])->get();

        $permissions = auth()->user()->getAllPermissions();

        return view('tenant.roles.access', compact('user', 'roles', 'permissions'));
    }

    public function updateAccess(Request $request, User $user)
    {

        if ($user->hasRole('tenant-admin')) {
            return back()->with('error', 'Tenant Admin ki permissions "Protected" hain aur yahan se change nahi ho sakti!');
        }

        $allowedPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();
        $requestedPermissions = $request->permissions ?? [];

        $filteredPermissions = array_intersect($requestedPermissions, $allowedPermissions);

        $roles = collect($request->roles ?? [])
            ->reject(fn($role) => $role === 'tenant-admin')
            ->toArray();

        $user->syncRoles($roles);
        $user->syncPermissions($filteredPermissions);

        return redirect()
            ->route('tenant.users.index')
            ->with('success', 'User access updated successfully!');
    }
}
