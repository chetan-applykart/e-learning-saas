<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Tenant\CentralTenantPermissionService;
use Illuminate\Http\Request;

class TenantPermissionController extends Controller
{
     public function show($tenantId)
    {
        $permissions = CentralTenantPermissionService::getPermissionsForTenant($tenantId);

        return response()->json([
            'tenant_id' => $tenantId,
            'permissions' => $permissions
        ]);
    }
}
