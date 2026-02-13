@extends('app.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">


    <div class="card">
        <h5 class="card-header">Individual Team Management</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->getRoleNames() as $role)
                                <span class="badge bg-label-info text-uppercase">{{ $role }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('tenant.users.access', $user->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bx bx-user-check me-1"></i> Edit Individual
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
