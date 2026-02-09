<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // List roles
    public function index()
    {
        $roles = Role::where('tenant_id', tenant('id'))->get();
        return view('tenant.roles.index', compact('roles'));
    }

    // Create role form
    public function create()
    {
        $tenantId = tenant('id');

        // 👉 Sirf wahi permissions jo super admin ne tenant ko allow ki hain
        $permissions = Permission::whereIn('id', function ($q) use ($tenantId) {
            $q->select('permission_id')
              ->from('tenant_permissions')
              ->where('tenant_id', $tenantId);
        })->get();

        return view('tenant.roles.create', compact('permissions'));
    }

    // Store role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $role = Role::create([
            'name'       => $request->name,
            'guard_name' => 'web',
            'tenant_id'  => tenant('id'),
        ]);

        // 👉 Admin yahin se manage_users jaise permissions dega
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('tenant.roles.index');
    }
}
