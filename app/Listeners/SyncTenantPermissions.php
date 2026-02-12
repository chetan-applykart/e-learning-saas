<?php
namespace App\Listeners;

use App\Models\Tenant\TenantPermission;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class SyncTenantPermissions
{
    public function handle()
    {
        $tenantId = tenant('id');

        $centralPermissions = TenantPermission::where('tenant_id', $tenantId)
            ->pluck('permission_name')
            ->toArray();

        foreach ($centralPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
