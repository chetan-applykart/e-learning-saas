<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Tenant;
use Spatie\Permission\Models\Permission;

class TenantPermissionController extends Controller
{
    public function index(Request $request)
    {
        $tenants = Tenant::all();

        $permissions = Permission::whereNull('tenant_id')->get();

        $tenantId = $request->tenant_id;

        $assignedPermissions = [];

        if ($tenantId) {
            $assignedPermissions = DB::table('tenant_permissions')
                ->where('tenant_id', $tenantId)
                ->pluck('permission_id')
                ->toArray();
        }

        return view(
            'admin.tenant-permissions.index',
            compact('tenants', 'permissions', 'assignedPermissions')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $tenantId = $request->tenant_id;

        DB::table('tenant_permissions')
            ->where('tenant_id', $tenantId)
            ->delete();

        if ($request->permissions) {
            foreach ($request->permissions as $permissionId) {
                DB::table('tenant_permissions')->insert([
                    'tenant_id'     => $tenantId,
                    'permission_id' => $permissionId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        return back()->with('success', 'Permissions saved successfully');
    }
}
