<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantPermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Tenant\RoleController;
use Illuminate\Support\Facades\Route;


//  Route::get('/', function () {
//             return view('welcome');
//         });

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        });

        // seed test tenant
        Route::get('/seed-tenant', function () {
            $tenant = \App\Models\Tenant::create([
                'name' => 'Test Tenant 1',
                'email' => 'test1@example.com',
                'password' => bcrypt('password'),
            ]);

            return response()->json($tenant);
        });

        // create tenant domain for the test tenant
        Route::get('/create-tenant-domain/', function () {
            $tenant = \App\Models\Tenant::where('email', 'test1@example.com')->first();
            if (!$tenant) {
                return response()->json(['error' => 'Tenant not found'], 404);
            }

            $tenant->domains()->create([
                'domain' => 'test1.localhost',
            ]);

            return response()->json(['message' => 'Tenant domain created']);
        });




        Route::get('/tenant-permissions', [TenantPermissionController::class, 'index'])->name('tenant.permissions');

        Route::post('/tenant-permissions', [TenantPermissionController::class, 'store'])->name('admin.tenant.permissions.store');

        // Route::post('/tenant-permissions', function () {
        //     dd("dejdcd");
        //     return view('admin.dashboard');
        // })->name('tenant.permissions.store');

        // Route::get('/create-tenant-domain/', function () {
        //     $tenant = \App\Models\Tenant::firstOrCreate(
        //         ['email' => 'test1@example.com'],
        //         [
        //             'id'       => 'test1',
        //             'name'     => 'Test User',
        //             'password' => Hash::make('password123'),
        //         ]
        //     );

        //     if (!$tenant->domains()->where('domain', 'test1.localhost')->exists()) {
        //         $tenant->domains()->create([
        //             'domain' => 'test1.localhost',
        //         ]);
        //     }

        //     return response()->json([
        //         'message' => 'Tenant and Domain created successfully!',
        //         'tenant_id' => $tenant->id
        //     ]);
        // });

        // Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

            // Dashboard

        // });
    });
}
