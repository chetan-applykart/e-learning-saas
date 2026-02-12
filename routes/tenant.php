<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\Celpip\CelpipController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\Exam\FormBuilderController;
use App\Http\Controllers\Tenant\RoleController;
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
        // 1. User create karein
        $user = \App\Models\User::create([
            'name' => 'Tenant User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        // 2. User ko 'tenant-admin' role assign karein
        // (Ye wahi role hai jisko aapne Super Admin panel se permissions di hain)
        $user->assignRole('tenant-admin');

        return response()->json([
            'message' => 'User created and role assigned!',
            'user' => $user,
            'roles' => $user->getRoleNames() // Check karne ke liye ki role mila ya nahi
        ]);
    });

    Route::get('/assign-admin-role', function () {
        $user = User::where('email', 'admin@gmail.com')->first();

        if (!$user) {
            return 'User not found';
        }

        $user->assignRole('admin');

        return 'Admin role assigned';
    });

    Route::get('/fix-admin-permissions', function () {
        // 1. User ko dhundein
        $user = User::where('email', 'admin@gmail.com')->first();

        if (!$user) {
            return 'User admin@gmail.com nahi mila!';
        }

        // 2. User ki saari "Direct Permissions" saaf karein
        // Isse aapka 'direct' wala array empty ho jayega
        $user->syncPermissions([]);

        // 3. Sahi Role assign karein (tenant-admin)
        // Pehle purane roles hata kar fresh role dena behtar hai
        $user->syncRoles(['tenant-admin']);

        return 'Direct permissions cleared and "tenant-admin" role assigned successfully!';
    });
    Route::get('/create-admin-role', function () {

        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
            'tenant_id'  => tenant('id'),
        ]);

        return $role;
    });


    Route::get('/admin/form-builder', [FormBuilderController::class, 'create'])->name('form.builder');
    Route::post('/admin/form-builder', [FormBuilderController::class, 'store'])->name('form.builder.store');

    Route::get('/get-modules/{exam}', [FormBuilderController::class, 'getModules']);
    Route::get('/get-parts/{module}', [FormBuilderController::class, 'getParts']);
    Route::get('/get-forms/{part}', [FormBuilderController::class, 'getForms']);
    Route::get('/get-fields/{form}', [FormBuilderController::class, 'getFields']);



    Route::prefix('celpip')
        ->name('tenant.celpip.')
        ->group(function () {

            Route::get('/listening/add', [CelpipController::class, 'listeningAdd'])
                ->name('listening.add');

            Route::post('/listening/store', [CelpipController::class, 'listeningStore'])
                ->name('listening.store');

            Route::get('/get-forms/{id}', [CelpipController::class, 'getFormsByPart'])
                ->name('get.forms');

            Route::get('/get-fields/{id}', [CelpipController::class, 'getFormFields'])
                ->name('get.fields');
        });

    Route::post('/admin/save-full-exam-structure', [CelpipController::class, 'storeFullStructure'])->name('exam.full.store');
    // Cascading dropdowns ke liye
    Route::get('/get-modules/{id}', [CelpipController::class, 'getModules']);
    Route::get('/get-parts/{id}', [CelpipController::class, 'getParts']);



    // Auth routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('app.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('app.logout');

    Route::middleware(['auth', 'verified'])->group(function () {
        // User List Page
        Route::get('/users', [RoleController::class, 'index'])->name('tenant.users.index');

        // Edit User Access Page
        Route::get('/users/{user}/access', [RoleController::class, 'editAccess'])->name('tenant.users.access');

        // Update User Access Logic
        Route::post('/users/{user}/access', [RoleController::class, 'updateAccess'])->name('tenant.users.access.update');
    });

    // Route::get('/dashboard', function () {
    //     //   dd(auth()->user());

    //     return view('app.dashboard');
    // })->name('app.dashboard');
    //  Route::get('/dashboard', DashboardController::class)
    //             ->middleware('permission:view_dashboard')
    //             ->name('dashboard');

    //         // Users (admin only)
    //         Route::resource('users', UserController::class)
    //             ->middleware('permission:manage_users');


    Route::get('/dashboard', [DashboardController::class, 'TenantDashboard'])
        ->name('tenant.dashboard');

    // Route::resource('users', TenantUserController::class)
    //     ->middleware('permission:manage_users');


    // Route::prefix('celpip')->name('app.celpip.')->group(function () {

    //     Route::get('/listening/add-question', [CelpipController::class, 'listeningAdd'])
    //         ->name('listening.add.question');

    //     Route::get('/reading/add-question', [CelpipController::class, 'readingAdd'])
    //         ->name('reading.add.question');

    //     Route::get('/speaking/add-question', [CelpipController::class, 'speakingAdd'])
    //         ->name('speaking.add.question');

    //     Route::get('/writing/add-question', [CelpipController::class, 'writingAdd'])
    //         ->name('writing.add.question');
    // });
    Route::post(
        '/admin/celpip/listening/{id?}',
        [CelpipController::class, 'store']
    )->name('admin.celpip.listening.store');

    Route::post(
        '/celpip/writing/submit',
        [CelpipController::class, 'submitWriting']
    )->name('celpip.writing.submit');
});
