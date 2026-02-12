@extends('app.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">
                    <i class="bx bx-shield-quarter me-2"></i>Manage Access for:
                    <span class="text-primary">{{ $user->name }}</span>
                    <small class="text-muted">({{ $user->email }})</small>
                </h5>

                <div class="card-body">
                    <form action="{{ route('tenant.users.access.update', $user->id) }}" method="POST">
                        @csrf

                        <div class="mb-5">
                            <h6 class="fw-bold mb-3"><i class="bx bx-user-check me-1"></i> Assign Roles</h6>
                            <div class="row g-3">
                                @foreach($roles as $role)
                                <div class="col-md-3">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="role-{{ $role->id }}">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                value="{{ $role->name }}" id="role-{{ $role->id }}"
                                                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                            <span class="custom-option-header">
                                                <span class="fw-medium text-capitalize">{{ str_replace('-', ' ', $role->name) }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="bx bx-key me-1"></i> Direct Permissions</h6>
                            <div class="row">
                                @foreach($permissions as $permission)
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                                            {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label text-capitalize" for="perm-{{ $permission->id }}">
                                            {{ str_replace(['-', '_'], ' ', $permission->name) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                <i class="bx bx-save me-1"></i> Save Access Settings
                            </button>
                            <a href="{{ route('tenant.users.index') }}" class="btn btn-label-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
