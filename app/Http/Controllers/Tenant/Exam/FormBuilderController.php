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
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{

    public function FromCreate()
    {
        $exams = Exam::all();
        $masterForms = Form::all();
        return view('tenant.from.create', compact('exams', 'masterForms'));
    }

    public function formstore(Request $request)
    {
        if ($request->form_mode === 'EXISTING' && $request->existing_form_id) {
            $form = Form::findOrFail($request->existing_form_id);
        } else {
            $form = new Form();
        }

        $form->exam_part_id = $request->exam_part_id;
        $form->name         = $request->form_name;
        $form->slug         = Str::slug($request->form_name);
        $form->save();

        $form->fields()->delete();

        if ($request->has('field_labels')) {
            foreach ($request->field_labels as $key => $label) {
                FormField::create([
                    'form_id' => $form->id,
                    'label'   => $label,
                    'name'    => $request->field_names[$key],
                    'type'    => $request->field_types[$key],
                    'required' => true,
                ]);
            }
        }

        $msg = ($request->form_mode === 'EXISTING') ? 'Form Updated Successfully!' : 'New Form Created Successfully!';
        return redirect()->back()->with('success', $msg);
    }

    public function create()
    {
        $exams = Exam::all();


        $masterForms = Form::all();

        return view('tenant.from.form-builder', compact('exams', 'masterForms'));
    }
    // public function getModules($examId)
    // {
    //     return ExamModule::where('exam_id', $examId)->get();
    // }

    // public function getParts($moduleId)
    // {
    //     return ExamPart::where('exam_module_id', $moduleId)->get();
    // }

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

    public function getModules($examId)
    {
        return response()->json(ExamModule::where('exam_id', $examId)->get());
    }

    public function getParts($moduleId)
    {
        return response()->json(ExamPart::where('exam_module_id', $moduleId)->get());
    }

    public function getFormData($partId)
    {
        $form = Form::where('exam_part_id', $partId)->first();
        $fields = $form ? $form->fields : [];
        return response()->json(['form' => $form, 'fields' => $fields]);
    }
    public function getFormsByPart($partId)
    {
        $forms = Form::where('exam_part_id', $partId)->get();
        return response()->json($forms);
    }

    public function getFormFields($formId)
    {
        $form = Form::with('fields')->findOrFail($formId);
        return response()->json([
            'form' => $form,
            'fields' => $form->fields
        ]);
    }

    public function manageQuestions($exam_id, $module_id)
{
    $exam = Exam::findOrFail($exam_id);
    $module = ExamModule::with('parts.forms')->findOrFail($module_id);

    return view('tenant.questions.manage', compact('exam', 'module'));
}
}
