<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function create()
    {
        return view('admin.tenants.add');
    }
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'id' => 'required|alpha_dash|unique:tenants,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'password' => 'required|min:6',
            'subdomain' => 'required|alpha_dash'
        ]);

        // 2. Create Tenant
        $tenant = Tenant::create([
            'id' => $request->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Create Domain (test1.localhost format)
        $domain = $request->subdomain . '.localhost';
        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        return redirect()->route('admin.tenant.create')
                         ->with('success', "Tenant created! Access at: http://$domain:8000");
    }
}
