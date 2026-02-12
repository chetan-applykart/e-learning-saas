<?php

namespace App\Http\Controllers\Tenant\Celpip;

use App\Http\Controllers\Controller;
use App\Services\CelpipAnswerCheckService;
use App\Services\LangcertAnswerCheckService;
use App\Services\LangcertSpeakingEvaluationService;
use App\Services\LangcertImageCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant\Exam\Exam as TenantExam;
use App\Models\Tenant\Exam\ExamModule;
use App\Models\Tenant\Exam\ExamPart;
use App\Models\Tenant\Exam\Form;
use App\Models\Tenant\Exam\FormField;
use App\Models\Tenant\Exam\FormSubmission;
use Illuminate\Support\Str;

class CelpipController extends Controller
{
    public function listeningAdd(Request $request)
    {
        // $exam = TenantExam::create([
        //     'name' => 'CELPIP',
        //     'slug' => 'celpip',
        //     'status' => 1
        // ]);

        // dd($request->all(), "djcjedjd");
        $exam = TenantExam::where('slug', 'celpip')->firstOrFail();
        // dd($exam);
        $module = ExamModule::where('exam_id', $exam->id)
            ->where('slug', 'listening')
            ->firstOrFail();

        $parts = ExamPart::where('exam_module_id', $module->id)
            ->orderBy('sort_order')
            ->get();

        return view('tenant.celpip.listening.add', compact(
            'exam',
            'module',
            'parts'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | GET FORMS BY PART (AJAX)
    |--------------------------------------------------------------------------
    */

    public function getFormsByPart($partId)
    {
        return Form::where('exam_part_id', $partId)
            ->orderBy('sort_order')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | GET FORM FIELDS (AJAX)
    |--------------------------------------------------------------------------
    */

    public function getFormFields($formId)
    {
        return FormField::where('form_id', $formId)
            ->orderBy('sort_order')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | STORE LISTENING DATA
    |--------------------------------------------------------------------------
    */

    public function listeningStore(Request $request)
    {
        $form = Form::findOrFail($request->form_id);
        $fields = FormField::where('form_id', $form->id)->get();

        $data = [];

        foreach ($fields as $field) {

            // File Upload Handling
            if ($field->type === 'file' && $request->hasFile($field->name)) {

                $file = $request->file($field->name);
                $path = $file->store('exam_uploads', 'public');
                $data[$field->name] = $path;
            } else {
                $data[$field->name] = $request->input($field->name);
            }
        }

        FormSubmission::create([
            'exam_id'        => $request->exam_id,
            'exam_module_id' => $request->module_id,
            'exam_part_id'   => $request->part_id,
            'form_id'        => $request->form_id,
            'data'           => $data
        ]);

        return back()->with('success', 'Listening Question Saved Successfully');
    }

    public function storeFullStructure(Request $request)
    {
        // 1. EXAM: Purana select kiya ya naya likha?
        if ($request->new_exam_name) {
            $exam = TenantExam::create([
                'name' => $request->new_exam_name,
                'slug' => Str::slug($request->new_exam_name)
            ]);
            $examId = $exam->id;
        } else {
            $examId = $request->existing_exam_id;
        }

        // 2. MODULE: Purana select kiya ya naya likha?
        if ($request->new_module_name) {
            $module = ExamModule::create([
                'exam_id' => $examId,
                'name' => $request->new_module_name,
                'slug' => Str::slug($request->new_module_name)
            ]);
            $moduleId = $module->id;
        } else {
            $moduleId = $request->existing_module_id;
        }

        // 3. PART: Purana select kiya ya naya likha?
        if ($request->new_part_name) {
            $part = ExamPart::create([
                'exam_module_id' => $moduleId,
                'name' => $request->new_part_name
            ]);
            $partId = $part->id;
        } else {
            $partId = $request->existing_part_id;
        }

        // 4. FORM (Always Create)
        $form = Form::create([
            'exam_part_id' => $partId,
            'name' => $request->form_name,
            'slug' => Str::slug($request->form_name)
        ]);

        return back()->with('success', 'Full Structure & Form Created!');
    }

    // Dropdown updates ke liye helpers
    public function getModules($id)
    {
        return response()->json(ExamModule::where('exam_id', $id)->get());
    }
    public function getParts($id)
    {
        return response()->json(ExamPart::where('exam_module_id', $id)->get());
    }
    public function readingAdd()
    {
        return view('tenant.celpip.reading.add');
    }

    public function speakingAdd()
    {
        return view('tenant.celpip.speaking.add');
    }

    public function writingAdd()
    {
        return view('tenant.celpip.writing.add');
    }

    // public function submitWriting(Request $request)
    // {
    //     // $request->validate([
    //     //     'answer' => 'required|min:50',
    //     // ]);
    //     $answer = "
    // dear Construction Manager,  I Hope this message finds you well.
    // I am writing to express my Concern regarding the construction work currently
    // taking place across the street froms the local. elementary school. I live nearby and have
    //  been closely observing the site over the past few days  I have noticed that the construction area does not appear to
    //   have a proper safety fence or barrier around it. Given that many children walk past this area before and after school,
    //    this situation could pose a serious risk of accidents or injuries. In addition, the level of noise during school
    //    hours is extremely high, which may be disrupting classroom activities and making it difficult for students to
    //    concentrate on their lessons.  I am particularly worried about the safety of young children who may be curious
    //     and unaware of the dangers associated with an active construction site. I kindly suggest that a secure safety
    //      fence be installed immediately and clear warning signs be placed around the area. Additionally, reducing noisy
    //       work during school hours or scheduling it outside class times could greatly help minimize disruption.
    //       Thank you for your attention to this important matter. I hope you will take these concerns seriously and
    //        take appropriate steps
    // to ensure both safety and consideration for the school community.  Yours sincerely, Name
    // ";

    //     $question = "You live across from an elementary school where a construction team is working outside. You notice that the construction site does not have a safety fence around it and that it is too noisy during class. You are worried about children getting hurt after school or having difficulty learning in class.
    //         Describe what you have seen
    // Explain why you are worried
    // Suggest how they could work more safely";

    //     // $answer = $request->answer;

    //     $result = LangcertImageCheckService::checkWritingImage($question, $answer);

    //     dd($result);

    //     return view('tenant.celpip.writing.result', compact('result'));
    // }

    // public function submitWriting(Request $request, CelpipAnswerCheckService $answerCheckService)
    // {
    //     // Student Answer
    //     $answer = "
    // The provided column chart illustrates the consumer preferences for various soft drink brands. It is evident from the data that Coca Cola is the most dominant brand in the market, capturing the highest preference at 35%. This is followed by Pepsi, which holds a significant share of 25%, making these two brands the primary leaders in the industry.

    // In contrast, other brands show a relatively lower preference among consumers. Sprite stands at 18%, while Diet Coke accounts for 12% of the total share. The least preferred brand according to the chart is Fanta, which sits at only 10%.

    // Overall, the chart highlights a clear hierarchy in the soft drink market. There is a substantial 25% gap between the most popular brand, Coca Cola, and the least popular one, Fanta. The data suggests that traditional cola-flavored drinks (Coca Cola and Pepsi) collectively command a massive 60% of the total brand preference, significantly outperforming lemon-lime and orange-flavored alternatives. This indicates a strong consumer loyalty towards the top two global giants in the beverage sector
    // // ";

    //     // Question text
    //     $question = "
    // Write about 200 words describing and explaining the situation shown in the image.
    // Focus only on the information and context presented in the image.
    // ";


    //     //  IMAGE URL (as requested)
    //     $imageUrl = "https://www.slideteam.net/media/catalog/product/cache/1280x720/s/o/soft_drinks_brand_preference_column_chart_slide01.jpg";
    //     $result = LangcertImageCheckService::checkWritingImage(
    //         $question,
    //         $answer,
    //         $imageUrl
    //     );

    //     dd($result);

    //     return view('tenant.celpip.writing.result', compact('result'));
    // }


    public function submitWriting(Request $request)
    {
        Log::info('submitWriting called');
        if (!$request->hasFile('audio')) {
            return "Audio file missing in request!";
        }

        Log::info('Request data', [
            'question' => $request->question,
            'has_audio' => $request->hasFile('audio'),
        ]);

        $request->validate([
            'question' => 'required|string|min:10',
            'audio'    => 'required|file|max:10240',
        ]);

        Log::info('Validation passed');

        $audioPath = $request->file('audio')->store('speaking_audio');
        Log::info('Audio stored at', ['path' => $audioPath]);

        $service = new LangcertSpeakingEvaluationService();
        Log::info('Service instantiated');
        $imagePath = "";
        $result = $service->evaluate(
            $request->question,
            storage_path('app/' . $audioPath),
            $imagePath
        );
        dd($result);

        Log::info('Evaluation completed', $result);

        return response()->json($result);
    }
}
