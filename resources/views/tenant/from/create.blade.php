@extends('app.layouts.app')

@section('content')
    <style>
        .x-small {
            font-size: 10px;
            letter-spacing: 1px;
            color: #6c757d;
        }

        .bg-light-50 {
            background-color: #f8f9fa;
        }

        .field-item:hover {
            border-left: 4px solid #4e73df;
            transition: 0.2s;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-layer-group me-2"></i>Smart Form Manager</h5>
                <div id="builder_actions" style="display: none;">
                    <button type="button" class="btn btn-primary btn-sm rounded-pill" onclick="addField()">
                        <i class="fas fa-plus me-1"></i> Add New Input
                    </button>
                </div>
            </div>

            <form action="{{ route('tenant.form.store') }}" method="POST">
                @csrf
                <div class="card-body bg-light-50">
                    <div class="row g-3 mb-4 p-3 bg-white rounded shadow-sm border">
                        <div class="col-md-2">
                            <label class="form-label x-small fw-bold">1. EXAM</label>
                            <select name="exam_id" id="exam_select" class="form-select border-0 bg-light shadow-none"
                                required>
                                <option value="">Select Exam</option>
                                @foreach ($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label x-small fw-bold">2. MODULE</label>
                            <select name="exam_module_id" id="module_select"
                                class="form-select border-0 bg-light shadow-none" disabled required>
                                <option value="">Select Module</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label x-small fw-bold">3. PART</label>
                            <select name="exam_part_id" id="part_select" class="form-select border-0 bg-light shadow-none"
                                disabled required>
                                <option value="">Select Part</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label x-small fw-bold">4. MODE</label>
                            <select name="form_mode" id="form_mode"
                                class="form-select border-0 bg-primary text-white shadow-none" disabled>
                                <option value="NEW">Create New Form</option>
                                <option value="EXISTING">Edit Existing Form</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="form_list_container" style="display: none;">
                            <label class="form-label x-small fw-bold text-danger">5. SELECT WHICH FORM?</label>
                            <select name="existing_form_id" id="existing_form_id"
                                class="form-select border-0 bg-warning shadow-none">
                                <option value="">-- Choose Form --</option>
                            </select>
                        </div>
                    </div>

                    <div id="form_name_container" class="mb-4" style="display: none;">
                        <label class="form-label x-small fw-bold">FORM NAME (TITLE)</label>
                        <input type="text" name="form_name" id="form_name_input"
                            class="form-control shadow-sm border-0 bg-white" placeholder="Enter Form Name" required>
                    </div>

                    <div id="fields_container">
                        <div class="text-center py-5 text-muted" id="empty_state">
                            <i class="fas fa-mouse-pointer fa-3x mb-3 opacity-25"></i>
                            <p class="fw-bold">Please select an Exam, Module, and Part to proceed.</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-3 text-end">
                    <button type="submit" class="btn btn-dark px-5 rounded-pill shadow-sm">
                        Save Changes <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <template id="field_template">
        <div class="field-item animate__animated animate__fadeIn mb-3">
            <div class="card border shadow-none bg-white">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label x-small fw-bold text-muted">INPUT LABEL</label>
                            <input type="text" name="field_labels[]" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label x-small fw-bold text-muted">TYPE</label>
                            <select name="field_types[]" class="form-select bg-light border-0" required>
                                <option value="text">Short Text</option>
                                <option value="textarea">Paragraph</option>
                                <option value="file">File Upload</option>
                                <option value="mcq">MCQ Block</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label x-small fw-bold text-muted">SLUG (DB NAME)</label>
                            <input type="text" name="field_names[]" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger border-0" onclick="removeField(this)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.getElementById('exam_select').addEventListener('change', function() {
            fetchModules(this.value);
        });

        document.getElementById('module_select').addEventListener('change', function() {
            fetchParts(this.value);
        });

        document.getElementById('part_select').addEventListener('change', function() {
            document.getElementById('form_mode').disabled = false;
            document.getElementById('builder_actions').style.display = 'block';
            document.getElementById('form_name_container').style.display = 'block';
            document.getElementById('empty_state').style.display = 'none';

            if (document.getElementById('form_mode').value === 'EXISTING') {
                fetchFormList();
            }
        });

        document.getElementById('form_mode').addEventListener('change', function() {
            const container = document.getElementById('fields_container');
            const listContainer = document.getElementById('form_list_container');

            if (this.value === 'EXISTING') {
                listContainer.style.display = 'block';
                fetchFormList();
            } else {
                listContainer.style.display = 'none';
                container.innerHTML = '';
                document.getElementById('form_name_input').value = '';
            }
        });

        function fetchFormList() {
            const partId = document.getElementById('part_select').value;
            const formSelect = document.getElementById('existing_form_id');

            formSelect.innerHTML = '<option>Loading...</option>';

            fetch(`/tenant/get-forms-by-part/${partId}`)
                .then(res => res.json())
                .then(data => {
                    formSelect.innerHTML = '<option value="">-- Choose Form --</option>';
                    data.forEach(f => {
                        formSelect.innerHTML += `<option value="${f.id}">${f.name}</option>`;
                    });
                });
        }

        document.getElementById('existing_form_id').addEventListener('change', function() {
            const formId = this.value;
            if (!formId) return;

            const container = document.getElementById('fields_container');
            container.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading Fields...</div>';

            fetch(`/tenant/get-form-fields/${formId}`)
                .then(res => res.json())
                .then(data => {
                    container.innerHTML = '';
                    document.getElementById('form_name_input').value = data.form.name;
                    data.fields.forEach(f => addField(f));
                });
        });

        function fetchModules(examId) {
            const ms = document.getElementById('module_select');
            fetch(`/tenant/get-modules/${examId}`).then(res => res.json()).then(data => {
                ms.disabled = false;
                ms.innerHTML = '<option value="">Select Module</option>';
                data.forEach(m => ms.innerHTML += `<option value="${m.id}">${m.name}</option>`);
            });
        }

        function fetchParts(moduleId) {
            const ps = document.getElementById('part_select');
            fetch(`/tenant/get-parts/${moduleId}`).then(res => res.json()).then(data => {
                ps.disabled = false;
                ps.innerHTML = '<option value="">Select Part</option>';
                data.forEach(p => ps.innerHTML += `<option value="${p.id}">${p.name}</option>`);
            });
        }

        function addField(data = null) {
            const container = document.getElementById('fields_container');
            const template = document.getElementById('field_template');
            const clone = template.content.cloneNode(true);

            if (data) {
                clone.querySelector('[name="field_labels[]"]').value = data.label;
                clone.querySelector('[name="field_types[]"]').value = data.type;
                clone.querySelector('[name="field_names[]"]').value = data.name;
            }

            container.appendChild(clone);
            if (document.getElementById('empty_state')) document.getElementById('empty_state').style.display = 'none';
        }

        function removeField(btn) {
            btn.closest('.field-item').remove();
        }
    </script>
@endsection
