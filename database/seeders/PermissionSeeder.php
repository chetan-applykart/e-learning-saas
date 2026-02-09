<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_roles',
            'manage_courses',
            'view_reports',
            'manage_payments',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
                'tenant_id' => null,
            ]);
        }
    }
}
