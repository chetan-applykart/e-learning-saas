<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TenantPermission extends Model
{
    protected $connection = 'central'; // 🔥 THIS WAS MISSING

    protected $table = 'tenant_permissions';

    protected $fillable = [
        'tenant_id',
        'permission_name',
    ];
}
