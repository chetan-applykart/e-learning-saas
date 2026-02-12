<?php

namespace App\Http\Controllers\Tenant\Exam;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Exam\Exam;
use App\Models\Tenant\Exam\ExamModule;
use App\Models\Tenant\Exam\ExamPart;
use App\Models\Tenant\Exam\Form;
use App\Models\Tenant\Exam\FormField;
use App\Models\Tenant\Exam\FormSubmission;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    public function create()
    {
        $exams = Exam::all();


        $masterForms = Form::all();

        return view('tenant.from.form-builder', compact('exams', 'masterForms'));
    }
    public function getModules($examId)
    {
        return ExamModule::where('exam_id', $examId)->get();
    }

    public function getParts($moduleId)
    {
        return ExamPart::where('exam_module_id', $moduleId)->get();
    }

    public function getForms($partId)
    {
        return Form::where('exam_part_id', $partId)->get();
    }

    public function getFields($formId)
    {
        return FormField::where('form_id', $formId)->orderBy('sort_order')->get();
    }

    public function store(Request $request)
    {
        $submission = FormSubmission::create([
            'exam_id' => $request->exam_id,
            'exam_module_id' => $request->module_id,
            'exam_part_id' => $request->part_id,
            'form_id' => $request->form_id,
            'data' => $request->except(['_token', 'exam_id', 'module_id', 'part_id', 'form_id'])
        ]);

        return back()->with('success', 'Saved Successfully');
    }
}
