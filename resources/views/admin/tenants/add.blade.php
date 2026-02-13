@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Create New Tenant (Client)</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-1"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
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
                                @php
                                    $host = parse_url(env('APP_URL'), PHP_URL_HOST);
                                @endphp

                                <span class="input-group-text">
                                    .{{ $host }}
                                </span>

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
