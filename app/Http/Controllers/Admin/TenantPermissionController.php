<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantPermissionController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('admin.tenant-permissions.index', compact('tenants'));
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);

        $data = $tenant->run(function () {
            $allPermissions = Permission::all();
            $allRoles = Role::all();

            $tenantAdminRole = Role::where('name', 'tenant-admin')->first();

            $activePermissions = $tenantAdminRole
                ? $tenantAdminRole->permissions->pluck('name')->toArray()
                : [];

            return [
                'permissions' => $allPermissions,
                'roles' => $allRoles,
                'activePermissions' => $activePermissions
            ];
        });

        return view('admin.tenant-permissions.permissions', [
            'tenant' => $tenant,
            'permissions' => $data['permissions'],
            'roles' => $data['roles'],
            'activePermissions' => $data['activePermissions']
        ]);
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->run(function () use ($request) {
            $role = Role::firstOrCreate([
                'name' => 'tenant-admin',
                'guard_name' => 'web'
            ]);

            $role->syncPermissions($request->permissions ?? []);

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });

        return redirect()
            ->back()
            ->with('success', 'Permissions updated and showing correctly now!');
    }
}
