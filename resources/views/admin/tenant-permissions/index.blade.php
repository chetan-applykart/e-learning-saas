{{-- admin layout --}}
@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    {{-- page heading --}}
    <h1 class="mb-4">Tenant Permission Management</h1>

    {{-- tenant select form --}}
    <form method="GET">

        <div class="row mb-4">
            <div class="col-md-4">

                {{-- label --}}
                <label><strong>Select Tenant</strong></label>

                {{-- dropdown --}}
                <select name="tenant_id"
                        class="form-control"
                        onchange="this.form.submit()">

                    <option value="">-- Select Tenant --</option>

                    {{-- tenants loop --}}
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}"
                            {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name ?? $tenant->id }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

    </form>

    {{-- agar tenant select ho --}}
    @if(request('tenant_id'))

    {{-- permission save form --}}
    <form method="POST" action="/tenant-permissions">
        @csrf

        {{-- hidden tenant id --}}
        <input type="hidden" name="tenant_id"
               value="{{ request('tenant_id') }}">

        <div class="card">

            <div class="card-header">
                <strong>Assign Permissions</strong>
            </div>

            <div class="card-body">
                <div class="row">

                    {{-- permission loop --}}
                    @foreach($permissions as $permission)
                        <div class="col-md-3 mb-2">

                            <label>
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                {{ $permission->name }}
                            </label>

                        </div>
                    @endforeach

                </div>
            </div>

            <div class="card-footer text-end">
                <button class="btn btn-primary">
                    Save Permissions
                </button>
            </div>

        </div>
    </form>

    @endif

</div>
@endsection

{{-- scripts --}}
@push('scripts')
<script>
    console.log('Tenant permission page loaded');
</script>
@endpush
