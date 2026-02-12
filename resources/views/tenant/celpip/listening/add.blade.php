@extends('app.layouts.app')

@section('content')

<div class="block">
    <div class="block-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">

<form method="POST"
      action="{{ route('tenant.celpip.listening.store') }}"
      enctype="multipart/form-data">

@csrf

<input type="hidden" name="exam_id" value="{{ $exam->id }}">
<input type="hidden" name="module_id" value="{{ $module->id }}">

{{-- PART SELECT --}}
<div class="card mb-3">
    <div class="card-body">
        <label>Select Part</label>
        <select name="part_id" id="partSelect" class="form-control" required>
            <option value="">Select Part</option>
            @foreach($parts as $part)
                <option value="{{ $part->id }}">
                    {{ $part->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- FORM SELECT --}}
<div class="card mb-3">
    <div class="card-body">
        <label>Select Form</label>
        <select name="form_id" id="formSelect" class="form-control" required>
            <option value="">Select Form</option>
        </select>
    </div>
</div>

{{-- DYNAMIC FIELDS --}}
<div id="dynamic-fields"></div>

<button class="btn btn-primary">Submit</button>

</form>

            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script>

$(document).ready(function(){

    /*
    |--------------------------------------------------------------------------
    | Load Forms When Part Changes
    |--------------------------------------------------------------------------
    */

    $('#partSelect').change(function(){

        let partId = $(this).val();

        if(!partId){
            $('#formSelect').html('<option value="">Select Form</option>');
            $('#dynamic-fields').html('');
            return;
        }

        $.get('/celpip/get-forms/' + partId, function(forms){

            $('#formSelect').html('<option value="">Select Form</option>');

            forms.forEach(function(form){
                $('#formSelect').append(
                    `<option value="${form.id}">${form.name}</option>`
                );
            });

            $('#dynamic-fields').html('');
        });

    });


    /*
    |--------------------------------------------------------------------------
    | Load Fields When Form Changes
    |--------------------------------------------------------------------------
    */

    $('#formSelect').change(function(){

        let formId = $(this).val();

        if(!formId){
            $('#dynamic-fields').html('');
            return;
        }

        $.get('/celpip/get-fields/' + formId, function(fields){

            $('#dynamic-fields').html('');

            fields.forEach(function(field){

                let input = '';

                /*
                |--------------------------------------------------------------------------
                | TEXT
                |--------------------------------------------------------------------------
                */
                if(field.type === 'text'){
                    input = `
                        <input type="text"
                               name="${field.name}"
                               class="form-control"
                               ${field.required ? 'required' : ''}>
                    `;
                }

                /*
                |--------------------------------------------------------------------------
                | TEXTAREA
                |--------------------------------------------------------------------------
                */
                else if(field.type === 'textarea'){
                    input = `
                        <textarea name="${field.name}"
                                  class="form-control"
                                  rows="4"
                                  ${field.required ? 'required' : ''}></textarea>
                    `;
                }

                /*
                |--------------------------------------------------------------------------
                | FILE
                |--------------------------------------------------------------------------
                */
                else if(field.type === 'file'){
                    input = `
                        <input type="file"
                               name="${field.name}"
                               class="form-control"
                               ${field.required ? 'required' : ''}>
                    `;
                }

                /*
                |--------------------------------------------------------------------------
                | MCQ
                |--------------------------------------------------------------------------
                */
                else if(field.type === 'mcq'){

                    if(field.options){
                        field.options.forEach(function(option){

                            input += `
                                <div class="d-flex align-items-center mb-2">
                                    <input type="radio"
                                           name="${field.name}"
                                           value="${option}"
                                           class="me-2">
                                    ${option}
                                </div>
                            `;
                        });
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | CHECKBOX (Multiple Answer)
                |--------------------------------------------------------------------------
                */
                else if(field.type === 'checkbox'){

                    if(field.options){
                        field.options.forEach(function(option){

                            input += `
                                <div class="d-flex align-items-center mb-2">
                                    <input type="checkbox"
                                           name="${field.name}[]"
                                           value="${option}"
                                           class="me-2">
                                    ${option}
                                </div>
                            `;
                        });
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Append Field
                |--------------------------------------------------------------------------
                */

                $('#dynamic-fields').append(`
                    <div class="card mb-3">
                        <div class="card-body">
                            <label>${field.label}</label>
                            ${input}
                        </div>
                    </div>
                `);

            });

        });

    });

});

</script>

@endsection
