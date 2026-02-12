@extends('app.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <form action="{{ route('exam.full.store') }}" method="POST">
        @csrf
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Advance Exam Builder (Bulk)</h4>
                <button type="submit" class="btn btn-success fw-bold px-4 shadow">SAVE FULL STRUCTURE</button>
            </div>

            <div class="card-body bg-light">
                {{-- STEP 1: EXAM --}}
                <div class="card mb-4 shadow-sm border-primary">
                    <div class="card-body">
                        <label class="fw-bold text-primary mb-2 small">STEP 1: EXAM SELECTION</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <select name="existing_exam_id" class="form-select border-primary shadow-sm">
                                    <option value="">-- Select Existing Exam --</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="new_exam_name" class="form-control border-primary shadow-sm" placeholder="Or New Exam Name">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODULE WRAPPER --}}
                <div id="module-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">STEP 2: ADD MODULES, PARTS & FORMS</h5>
                        <button type="button" id="add-module" class="btn btn-primary btn-sm rounded-pill px-3">+ Add Module</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let mIdx = 0;
let pIdx = 0;
let fIdx = 0;

/* ================= 1. ADD MODULE ================= */
$('#add-module').click(function(){
    let mHtml = `
        <div class="card mb-5 border-start border-5 border-primary shadow module-item" data-m="${mIdx}">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <input type="text" name="modules[${mIdx}][name]" class="form-control fw-bold border-0 fs-5" placeholder="Module Name (e.g. Listening)" style="width: 70%">
                <button type="button" class="btn btn-outline-danger btn-sm remove-module">Remove Module</button>
            </div>
            <div class="card-body bg-white p-4">
                <div class="part-wrapper ms-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="fw-bold text-muted small">PARTS IN THIS MODULE</label>
                        <button type="button" class="btn btn-info btn-sm text-white add-part" data-m="${mIdx}">+ Add Part</button>
                    </div>
                    <div class="part-list"></div>
                </div>
            </div>
        </div>`;
    $('#module-wrapper').append(mHtml);
    mIdx++;
});

/* ================= 2. ADD PART ================= */
$(document).on('click', '.add-part', function(){
    let m = $(this).data('m');
    let pHtml = `
        <div class="card mb-4 border-start border-4 border-info shadow-sm part-item" data-p="${pIdx}">
            <div class="card-body bg-light">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-info text-white fw-bold">Part Name</span>
                    <input type="text" name="modules[${m}][parts][${pIdx}][name]" class="form-control fw-bold" placeholder="e.g. Part 1">
                    <button type="button" class="btn btn-outline-danger remove-part">X</button>
                </div>
                <div class="form-wrapper ms-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold text-secondary x-small">FORMS (Question Types)</label>
                        <button type="button" class="btn btn-warning btn-sm add-form" data-m="${m}" data-p="${pIdx}">+ Add Form</button>
                    </div>
                    <div class="form-list"></div>
                </div>
            </div>
        </div>`;
    $(this).closest('.part-wrapper').find('.part-list').append(pHtml);
    pIdx++;
});

/* ================= 3. ADD FORM ================= */
$(document).on('click', '.add-form', function(){
    let m = $(this).data('m');
    let p = $(this).data('p');
    let fHtml = `
        <div class="card mb-3 border-warning shadow-sm form-item">
            <div class="card-body p-3">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-warning fw-bold">Form Name</span>
                    <input type="text" name="modules[${m}][parts][${p}][forms][${fIdx}][name]" class="form-control" placeholder="e.g. Multiple Choice">
                    <button type="button" class="btn btn-outline-danger remove-form">X</button>
                </div>
                <div class="field-wrapper border p-2 rounded bg-white">
                    <label class="x-small fw-bold text-muted mb-2">DYNAMIC FIELDS (Questions/Labels)</label>
                    <div class="field-list mb-2"></div>
                    <button type="button" class="btn btn-link btn-sm text-decoration-none add-field" data-m="${m}" data-p="${p}" data-f="${fIdx}">+ Add Input Field</button>
                </div>
            </div>
        </div>`;
    $(this).closest('.form-wrapper').find('.form-list').append(fHtml);
    fIdx++;
});

/* ================= 4. ADD FIELDS (Title, Input etc) ================= */
$(document).on('click', '.add-field', function(){
    let m = $(this).data('m');
    let p = $(this).data('p');
    let f = $(this).data('f');
    let fieldHtml = `
        <div class="row g-2 mb-2 field-item">
            <div class="col-md-5">
                <input type="text" name="modules[${m}][parts][${p}][forms][${f}][fields][][label]" class="form-control form-control-sm" placeholder="Field Label (e.g. Title)">
            </div>
            <div class="col-md-4">
                <select name="modules[${m}][parts][${p}][forms][${f}][fields][][type]" class="form-select form-select-sm">
                    <option value="text">Short Text</option>
                    <option value="textarea">Large Text</option>
                    <option value="file">File (Audio/Image)</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="modules[${m}][parts][${p}][forms][${f}][fields][][name]" class="form-control form-control-sm" placeholder="Slug">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-field">X</button>
            </div>
        </div>`;
    $(this).closest('.field-wrapper').find('.field-list').append(fieldHtml);
});

/* ================= REMOVAL LOGIC ================= */
$(document).on('click', '.remove-module', function(){ $(this).closest('.module-item').remove(); });
$(document).on('click', '.remove-part', function(){ $(this).closest('.part-item').remove(); });
$(document).on('click', '.remove-form', function(){ $(this).closest('.form-item').remove(); });
$(document).on('click', '.remove-field', function(){ $(this).closest('.field-item').remove(); });
</script>

<style>
    .x-small { font-size: 11px; }
    .btn-link:hover { color: #055160 !important; }
</style>
@endsection
