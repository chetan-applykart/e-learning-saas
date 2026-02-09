<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\Celpip\CelpipController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\UserController as TenantUserController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Route::get('/', function () {
    //     return view('app.dashboard');
    // });

    // dd(
    //     request()->getHost(),   // test1.localhost
    //     config('app.url')       // http://localhost:8000
    // );
    // seed one user for testing
    Route::get('/seed-user', function () {
        $user = \App\Models\User::create([
            'name' => 'Tenant User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        return response()->json($user);
    });

    Route::get('/assign-admin-role', function () {
    $user = User::where('email', 'admin@gmail.com')->first();

    if (!$user) {
        return 'User not found';
    }

    $user->assignRole('admin');

    return 'Admin role assigned';
});

Route::get('/create-admin-role', function () {

    $role = Role::firstOrCreate([
        'name'       => 'admin',
        'guard_name' => 'web',
        'tenant_id'  => tenant('id'),
    ]);

    return $role;
});
    // Auth routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('app.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('app.logout');

    Route::get('/dashboard', function () {
        //   dd(auth()->user());

        return view('app.dashboard');
    })->name('app.dashboard');
    //  Route::get('/dashboard', DashboardController::class)
    //             ->middleware('permission:view_dashboard')
    //             ->name('dashboard');

    //         // Users (admin only)
    //         Route::resource('users', UserController::class)
    //             ->middleware('permission:manage_users');


        Route::get('/dashboard', DashboardController::class)

            ->name('tenant.dashboard');

        // Route::resource('users', TenantUserController::class)
        //     ->middleware('permission:manage_users');


        Route::prefix('celpip')->name('app.celpip.')->group(function () {

        Route::get('/listening/add-question', [CelpipController::class, 'listeningAdd'])
            ->name('listening.add.question');

        Route::get('/reading/add-question', [CelpipController::class, 'readingAdd'])
            ->name('reading.add.question');

        Route::get('/speaking/add-question', [CelpipController::class, 'speakingAdd'])
            ->name('speaking.add.question');

        Route::get('/writing/add-question', [CelpipController::class, 'writingAdd'])
            ->name('writing.add.question');
    });
    Route::post(
    '/admin/celpip/listening/{id?}',
    [CelpipController::class, 'store']
)->name('admin.celpip.listening.store');

Route::post(
    '/celpip/writing/submit',
    [CelpipController::class, 'submitWriting']
)->name('celpip.writing.submit');

});
