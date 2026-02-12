{{-- admin layout --}}
@extends('admin.layouts.app')

@section('content')

    <div class="tenant-container">
        <div class="header-section">
            <h2>All Tenants</h2>
            <p class="sub-text">Manage tenant permissions and roles easily</p>
        </div>

        <div class="table-card">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tenant Name</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $key => $tenant)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="tenant-name">{{ $tenant->id }}</td>
                            <td>
                                <span class="role-badge">Tenant</span>
                            </td>
                            <td>
                                <a href="{{ route('superadmin.tenants.permissions', $tenant->id) }}" class="btn-manage">
                                    Manage Permissions
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection


@push('styles')
    <style>
        /* Page Background */
        body {
            background: #f4f7fc;
        }

        /* Container */
        .tenant-container {
            padding: 40px;
        }

        /* Header */
        .header-section h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #222;
        }

        .sub-text {
            color: #777;
            margin-bottom: 25px;
        }

        /* Card */
        .table-card {
            background: #ffffff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        /* Table */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        .custom-table thead {
            background: linear-gradient(90deg, #4f46e5, #6366f1);
            color: #fff;
        }

        .custom-table th {
            padding: 14px;
            text-align: left;
            font-weight: 600;
        }

        .custom-table td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        /* Hover Effect */
        .custom-table tbody tr {
            transition: 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background: #f1f3ff;
            transform: scale(1.01);
        }

        /* Tenant Name */
        .tenant-name {
            font-weight: 600;
            color: #333;
        }

        /* Role Badge */
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #e0e7ff;
            color: #3730a3;
        }

        /* Button */
        .btn-manage {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(90deg, #10b981, #059669);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-manage:hover {
            background: linear-gradient(90deg, #059669, #047857);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }
    </style>
@endpush