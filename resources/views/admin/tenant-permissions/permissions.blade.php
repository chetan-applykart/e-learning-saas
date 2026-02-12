@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Permissions for Tenant: <span class="text-primary">{{ $tenant->id }}</span></h5>
            <a href="{{ route('superadmin.tenants') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.tenants.permissions.update', $tenant->id) }}" method="POST">
                @csrf

                <p class="text-muted small text-uppercase fw-bold">Select permissions to assign to Tenant Admin</p>

                <div class="row g-3">
                    @foreach($permissions as $permission)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->name }}"
                                   id="perm-{{ $permission->id }}"
                                   {{-- Yeh line permissions ko show/tick karti hai --}}
                                   {{ in_array($permission->name, $activePermissions) ? 'checked' : '' }}>

                            <label class="form-check-label text-capitalize" for="perm-{{ $permission->id }}">
                                {{ str_replace('-', ' ', $permission->name) }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Save & Sync Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
