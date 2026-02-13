@extends('app.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> Role Management</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Role</h5>
                    <small class="text-muted float-end">Define system roles</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenant.role.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="role-name">Role Name</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-shield-quarter"></i></span>
                                <input type="text" name="name" id="role-name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Manager, Editor" required />
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Role</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assign Role via Email</h5>
                    <small class="text-muted float-end">Quick assign</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenant.role.assign_email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">User Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="user@example.com" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">-- Choose Role --</option>
                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Assign Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Existing Roles</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Guard</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                    <tr>
                        <td><span class="badge bg-label-primary">{{ $role->name }}</span></td>
                        <td>{{ $role->guard_name }}</td>
                        <td>{{ $role->created_at->format('d M, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
