{{-- admin layout --}}
@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h2>All Tenants</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tenant Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenants as $key => $tenant)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $tenant->id }}</td>
                    <td>
                        <a href="{{ route('superadmin.tenants.permissions', $tenant->id) }}"
                           class="btn btn-primary btn-sm">
                            Manage Permissions
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

{{-- scripts --}}
@push('scripts')
<script>
    console.log('Tenant permission page loaded');
</script>
@endpush
