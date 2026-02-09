@extends('app.layouts.app')

@section('content')
<form method="POST" action="{{ route('celpip.writing.submit') }}">
    @csrf

    <textarea name="answer" required minlength="50"></textarea>

    <button type="submit">Submit</button>
</form>

@endsection
