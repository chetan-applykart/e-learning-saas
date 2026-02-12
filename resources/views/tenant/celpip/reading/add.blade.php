@extends('app.layouts.app')

@section('content')

<h3>Speaking Test</h3>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form
    method="POST"
    action="{{ route('celpip.writing.submit') }}"
    enctype="multipart/form-data"
>
    @csrf

    {{-- Question Input --}}
    <div style="margin-bottom:15px;">
        <label><strong>Enter Question</strong></label><br>
        <textarea
            name="question"
            rows="3"
            required
            placeholder="Type the speaking question here..."
        ></textarea>
    </div>

    {{-- Audio Upload --}}
    <div style="margin-bottom:15px;">
        <label><strong>Upload Answer (Audio)</strong></label><br>
        <input
            type="file"
            name="audio"
            accept="audio/*"
            required
        >
    </div>

    <button type="submit">
        Submit
    </button>
</form>

@endsection
