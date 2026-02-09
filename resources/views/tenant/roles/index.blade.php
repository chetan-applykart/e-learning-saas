@extends('app.layouts.app')

@section('content')

<h2>Roles</h2>

<a href="{{ route('tenant.roles.create') }}" class="btn btn-primary mb-3">
    Create Role
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Role Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
