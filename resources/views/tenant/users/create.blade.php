@extends('app.layouts.app')

@section('content')

<h2>Create User</h2>

<form method="POST" action="{{ route('users.store') }}">
@csrf

<div class="mb-3">
    <label>Name</label>
    <input name="name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input name="email" class="form-control" required>
</div>

<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-success">Create User</button>
<a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
