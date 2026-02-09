@extends('app.layouts.app')

@section('content')
    <h1>Tenant Dashboard</h1>
    <p>Welcome to the tenant dashboard!</p>
    <h1>Tenant ID: {{ tenant('id') }}</h1>
    <h2>Tenant Name: {{ tenant('name') }}</h2>
    <h2>Logged In User Name: {{ auth()->user()?->name }}</h2>
@endsection

@push('scripts')

@endpush
