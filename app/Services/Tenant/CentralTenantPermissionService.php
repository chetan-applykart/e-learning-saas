<?php
namespace App\Services\Tenant;

use App\Models\Tenant\TenantPermission;
class CentralTenantPermissionService
{
    public static function getPermissionsForTenant($tenantId)
    {
        return TenantPermission::where('tenant_id', $tenantId)
            ->pluck('permission_name')
            ->toArray();
    }
}
