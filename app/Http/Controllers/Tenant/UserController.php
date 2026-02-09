<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('tenant_id', tenant('id'))->get();
        return view('tenant.users.index', compact('users'));
    }

    public function create()
    {
        // admin ke tenant ke roles
        $roles = Role::where('tenant_id', tenant('id'))->get();
        return view('tenant.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'tenant_id' => tenant('id'),
        ]);

        // 👉 Admin role assign karta hai
        $user->assignRole($request->role);

        return redirect()->route('tenant.users.index');
    }

    public function destroy(User $user)
    {
        if ($user->tenant_id !== tenant('id')) {
            abort(403);
        }

        $user->delete();
        return back();
    }
}
