@extends('app.layouts.app')

@section('content')

<h2>Tenant Dashboard</h2>
<hr>

<p><strong>Tenant ID:</strong> {{ tenant('id') }}</p>
<p><strong>Tenant Name:</strong> {{ tenant('name') }}</p>
<p><strong>Logged User:</strong> {{ auth()->user()->name }}</p>

@endsection
