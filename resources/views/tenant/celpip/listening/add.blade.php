@extends('app.layouts.app')

@section('content')
    <link href="{{ asset('assets/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/plugins/ckeditor/ckeditor.js') }}"></script>

    <div class="block">
        <div class="block-content">
            <div class="row justify-content-center">
                <div class="col-xl-12">

                    <form method="POST"
                        action="{{ route('admin.celpip.listening.store', $getListeningData[0]->id ?? null) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="exam" value="">

                        {{-- Alerts --}}
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Header --}}
                        <div class="row mb-3">
                            <div class="col-6">
                                <h3 id="headingTitle">Select form type</h3>
                            </div>
                            <div class="col-6">
                                <select name="form_short_name" class="form-control" required>
                                    <option value="">Select Form Type</option>

                                    @foreach ($forms as $form)
                                        <option value="{{ $form->form_short_name }}">
                                            {{ $form->part_name }} — {{ $form->form_type }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <label>Title</label>
                                <input type="text" name="questionTitle" class="form-control"
                                    value="{{ $getListeningData[0]->title ?? '' }}" required>
                            </div>
                        </div>

                        {{-- Difficulty --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <label>Difficulty</label>
                                <select name="difficulty" class="form-control">
                                    <option value="">Select</option>
                                    @foreach (['Low', 'Medium', 'Hard'] as $d)
                                        <option value="{{ $d }}" @selected(($getListeningData[0]->difficulty ?? '') == $d)>
                                            {{ $d }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Audio --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <label>Upload Audio</label>
                                <input type="file" name="audioPath" class="form-control">

                                @if (!empty($getListeningData[0]->audioPath))
                                    <audio controls class="w-100 mt-2">
                                        <source src="{{ asset($getListeningData[0]->audioPath) }}">
                                    </audio>
                                @endif
                            </div>
                        </div>

                        {{-- Video --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <label>Upload Video</label>
                                <input type="file" name="videoPath" class="form-control">

                                @if (!empty($getListeningData[0]->videoPath))
                                    <video controls class="w-100 mt-2">
                                        <source src="{{ asset($getListeningData[0]->videoPath) }}">
                                    </video>
                                @endif
                            </div>
                        </div>

                        {{-- Transcript --}}
                        <div class="card mb-3" id="transcriptrow">
                            <div class="card-body">
                                <label>Transcript</label>
                                <textarea id="transcript" name="transcript" class="form-control" rows="10">{{ $getListeningData[0]->transcript ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- Question --}}
                        <div class="card mb-3" id="questionrow">
                            <div class="card-body">
                                <label>Question</label>
                                <textarea id="question" name="question" class="form-control" rows="6">{{ $getListeningData[0]->question ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- Answer --}}
                        <div class="card mb-3" id="answersrow" style="display:none;">
                            <div class="card-body">
                                <label>Answer</label>
                                <textarea id="answer" name="answer" class="form-control" rows="6">{{ $getListeningData[0]->answer ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- Explanation --}}
                        <div class="card mb-3" id="explanationrow" style="display:none;">
                            <div class="card-body">
                                <label>Explanation</label>
                                <textarea id="explanation" name="explanation" class="form-control" rows="6">{{ $getListeningData[0]->explanation ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- Timer --}}
                        <div class="card mb-3">
                            <div class="card-body row">
                                <div class="col-2">
                                    <label>Time (Min)</label>
                                    <input type="number" name="setTimerMin" class="form-control"
                                        value="{{ explode(':', $getListeningData[0]->exam_duration ?? '1:0')[0] }}">
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button class="btn btn-primary">
                            {{ !empty($getListeningData) ? 'Update' : 'Submit' }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- CKEditor --}}
    <script>
        CKEDITOR.replace('question');
        CKEDITOR.replace('transcript');
        CKEDITOR.replace('answer');
        CKEDITOR.replace('explanation');
    </script>
@endsection
