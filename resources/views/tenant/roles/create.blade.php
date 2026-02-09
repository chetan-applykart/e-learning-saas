@extends('app.layouts.app')

@section('content')

<h2>Create Role</h2>

<form method="POST" action="{{ route('tenant.roles.store') }}">
@csrf

<div class="mb-3">
    <label>Role Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Permissions</label>
    <div class="row">
        @foreach($permissions as $permission)
            <div class="col-md-4">
                <label>
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $permission->name }}">
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>

<button class="btn btn-success">Create Role</button>
<a href="{{ route('tenant.roles.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
