<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    // public function __invoke()
    // {
    //     return view('app.dashboard');
    // }

    public function TenantDashboard()
    {
        $allPermissions = Permission::all();

        $userPermissions = auth()->user()->getAllPermissions();

        $userRoles = auth()->user()->getRoleNames();

        // dd($userPermissions->toArray());

        return view('tenant.dashboard', compact('allPermissions', 'userPermissions', 'userRoles'));
    }
    // public function TenantDashboard()
    // {
    //     $user = \App\Models\User::where('email', 'admin@gmail.com')->first();
    // // Ye line user ki saari direct permissions delete kar degi
    // $user->syncPermissions([]);
    //     $user = auth()->user();

    //     // Sirf Direct Permissions (Jo sidha user ko di gayi hain)
    //     $directPermissions = $user->getDirectPermissions()->pluck('name');

    //     // Wo permissions jo user ko uske ROLES se mil rahi hain
    //     $rolePermissions = $user->getPermissionsViaRoles()->pluck('name');

    //     dd([
    //         'direct' => $directPermissions,
    //         'via_roles' => $rolePermissions,
    //         'all' => $user->getAllPermissions()->pluck('name')
    //     ]);
    // }
}
