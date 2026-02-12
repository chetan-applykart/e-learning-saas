@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create New Tenant (Client)</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.tenant.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Databse name (e.g. client01)</label>
                        <input type="text" name="id" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Client Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Subdomain (Example: typing <b>test1</b> will create <b>test1.localhost</b>)</label>
                        <div class="input-group">
                            <input type="text" name="subdomain" class="form-control" placeholder="test1">
                            <span class="input-group-text">.localhost</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success px-5">Register Tenant</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
