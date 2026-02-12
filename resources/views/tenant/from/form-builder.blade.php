@extends('app.layouts.app')

@section('content')

<style>
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --light-bg: #f8f9fc;
    }

    body { background: var(--light-bg); }

    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 0.2rem 1.5rem rgba(0,0,0,.05);
    }

    .card-header {
        font-weight: 600;
        letter-spacing: .5px;
    }

    .module-card {
        background: #fff;
        border-left: 4px solid var(--primary);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .part-row {
        background: #f1f4f9;
        padding: 8px;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .sticky-builder {
        position: sticky;
        top: 20px;
    }

    .badge-form {
        background: #eef2ff;
        color: var(--primary);
        font-size: 12px;
        margin: 2px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">

        <!-- LEFT SIDE BUILDER -->
        <div class="col-lg-5">
            <div class="card sticky-builder">
                <div class="card-header bg-primary text-white">
                    Advance Exam Builder
                </div>

                <form action="{{ route('exam.full.store') }}" method="POST" id="examForm" class="card-body">
                    @csrf

                    <!-- STEP 1 -->
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Step 1: Select or Create Exam</label>
                        <select name="exam_id" id="exam_select" class="form-select">
                            <option value="NEW">-- Create New Exam --</option>
                            @foreach($exams as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                            @endforeach
                        </select>

                        <input type="text"
                               name="new_exam_name"
                               id="new_exam_input"
                               class="form-control mt-2"
                               placeholder="Enter New Exam Name">
                    </div>

                    <!-- STEP 2 -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold">Step 2: Modules & Parts</label>
                        <button type="button" id="add-module" class="btn btn-sm btn-success">
                            + Add Module
                        </button>
                    </div>

                    <div id="dynamic-modules"></div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">
                        SAVE FULL STRUCTURE
                    </button>
                </form>
            </div>
        </div>


        <!-- RIGHT SIDE PREVIEW -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Exam Structure Preview
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Exam</th>
                                    <th>Module</th>
                                    <th>Parts & Forms</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $e)
                                    @php $rowspan = $e->modules->flatMap->parts->count(); @endphp
                                    @foreach($e->modules as $mIndex => $m)
                                        @foreach($m->parts as $pIndex => $p)
                                            <tr>
                                                @if($mIndex == 0 && $pIndex == 0)
                                                    <td rowspan="{{ $rowspan }}" class="fw-bold ps-4">
                                                        {{ $e->name }}
                                                    </td>
                                                @endif

                                                @if($pIndex == 0)
                                                    <td rowspan="{{ $m->parts->count() }}"
                                                        class="fw-bold text-primary">
                                                        {{ $m->name }}
                                                    </td>
                                                @endif

                                                <td>
                                                    <div class="fw-semibold small">
                                                        {{ $p->name }}
                                                    </div>

                                                    @foreach($p->forms as $f)
                                                        <span class="badge badge-form">
                                                            {{ $f->name }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let moduleIndex = 0;
let partIndex = 0;

function initSelect2() {
    $('.select2').select2({
        placeholder: "Select Forms",
        width: '100%'
    });
}

$(document).ready(function() {

    $('#new_exam_input').hide();

    $('#exam_select').change(function() {
        if ($(this).val() === 'NEW') {
            $('#new_exam_input').fadeIn();
        } else {
            $('#new_exam_input').fadeOut();
        }
    });

    // ADD MODULE
    $('#add-module').click(function() {

        let m = moduleIndex++;

        let moduleHtml = `
        <div class="module-card" data-m="${m}">
            <div class="d-flex mb-2">
                <input type="text"
                       name="modules[${m}][name]"
                       class="form-control fw-bold"
                       placeholder="Module Name">
                <button type="button" class="btn btn-sm text-danger ms-2 remove-module">×</button>
            </div>

            <div class="parts-container"></div>

            <button type="button"
                    class="btn btn-link btn-sm p-0 add-part"
                    data-m="${m}">
                + Add Part
            </button>
        </div>`;

        $('#dynamic-modules').append(moduleHtml);
    });


    // ADD PART
    $(document).on('click', '.add-part', function() {

        let m = $(this).data('m');
        let p = partIndex++;

        let partHtml = `
        <div class="part-row">
            <div class="row g-2">
                <div class="col-5">
                    <input type="text"
                           name="modules[${m}][parts][${p}][name]"
                           class="form-control form-control-sm"
                           placeholder="Part Name">
                </div>
                <div class="col-7">
                    <select name="modules[${m}][parts][${p}][forms][]"
                            class="form-select select2"
                            multiple>
                        @foreach($masterForms as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>`;

        $(this).siblings('.parts-container').append(partHtml);
        initSelect2();
    });

    // REMOVE MODULE
    $(document).on('click', '.remove-module', function() {
        $(this).closest('.module-card').remove();
    });

});
</script>

@endsection
