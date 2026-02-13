<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function create()
    {
        return view('admin.tenants.add');
    }
    // public function store(Request $request)
    // {
    //     // 1. Validation
    //     $request->validate([
    //         'id' => 'required|alpha_dash|unique:tenants,id',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:tenants,email',
    //         'password' => 'required|min:6',
    //         'subdomain' => 'required|alpha_dash'
    //     ]);

    //     // 2. Create Tenant
    //     $tenant = Tenant::create([
    //         'id' => $request->id,
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // 3. Create Domain (test1.localhost format)
    //     $domain = $request->subdomain . '.localhost';
    //     $tenant->domains()->create([
    //         'domain' => $domain,
    //     ]);

    //     return redirect()->route('admin.tenant.create')
    //                      ->with('success', "Tenant created! Access at: http://$domain:8000");
    // }

    //    public function store(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|alpha_dash|unique:tenants,id',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //         'password' => 'required|min:6',
    //         'subdomain' => 'required|alpha_dash'
    //     ]);

    //     try {
    //         $tenant = Tenant::create([
    //             'id' => $request->id,
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         $domain = $request->subdomain . '.localhost';
    //         $tenant->domains()->create([
    //             'domain' => $domain,
    //         ]);

    //         $tenant->run(function () use ($request) {
    //             try {
    //                 $user = \App\Models\User::firstOrCreate(
    //                     ['email' => $request->email],
    //                     [
    //                         'name' => $request->name,
    //                         'password' => Hash::make($request->password),
    //                     ]
    //                 );

    //                 $roleExists = \Spatie\Permission\Models\Role::where('name', 'tenant-admin')->exists();

    //                 if ($roleExists) {
    //                     $user->syncRoles(['tenant-admin']);
    //                 } else {
    //                     throw new \Exception("Role 'tenant-admin' not found in tenant database.");
    //                 }

    //             } catch (\Exception $innerEx) {
    //                 dd("Tenant DB Error: " . $innerEx->getMessage());
    //             }
    //         });

    //         return redirect()->route('admin.tenant.create')
    //             ->with('success', "Tenant Created! Domain: $domain");

    //     } catch (\Exception $e) {
    //         dd("Main Error: " . $e->getMessage(), "Line: " . $e->getLine());
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_dash|unique:tenants,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'subdomain' => 'required|alpha_dash'
        ]);

        try {
            $tenant = Tenant::create([
                'id' => $request->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

           $centralDomain = parse_url(env('APP_URL'), PHP_URL_HOST);

            $domain = $request->subdomain . '.' . $centralDomain;
            $tenant->domains()->create(['domain' => $domain]);

            $tenant->run(function () use ($request) {


                $seeder = new \Database\Seeders\PermissionSeeder();
                $seeder->run();

                $user = \App\Models\User::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'name' => $request->name,
                        'password' => Hash::make($request->password),
                    ]
                );


                $user->assignRole('tenant-admin');
            });

            return redirect()->route('admin.tenant.create')
               ->with('success', "Tenant Created! Subdomain: $domain");
        } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', "worning! Error: " . $e->getMessage());
    }
    }
}
