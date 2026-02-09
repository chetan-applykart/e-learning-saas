<?php

namespace App\Http\Controllers\Tenant\Celpip;

use App\Http\Controllers\Controller;
use App\Services\CelpipAnswerCheckService;
use Illuminate\Http\Request;

class CelpipController extends Controller
{
    public function listeningAdd()
    {
        $forms = \App\Models\Tenant\FormStructure::where('exam', 'CELPIP')
            ->where('exam_type', 'listening')
            ->orderBy('sort_order')
            ->get();

        return view('tenant.celpip.listening.add', compact('forms'));
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

    public function submitWriting(Request $request, CelpipAnswerCheckService $answerCheckService)
    {
        // $request->validate([
        //     'answer' => 'required|min:50',
        // ]);
        $answer = "
dear Construction Manager,  I Hope this message finds you well.
I am writing to express my Concern regarding the construction work currently
taking place across the street froms the local. elementary school. I live nearby and have
 been closely observing the site over the past few days  I have noticed that the construction area does not appear to
  have a proper safety fence or barrier around it. Given that many children walk past this area before and after school,
   this situation could pose a serious risk of accidents or injuries. In addition, the level of noise during school
   hours is extremely high, which may be disrupting classroom activities and making it difficult for students to
   concentrate on their lessons.  I am particularly worried about the safety of young children who may be curious
    and unaware of the dangers associated with an active construction site. I kindly suggest that a secure safety
     fence be installed immediately and clear warning signs be placed around the area. Additionally, reducing noisy
      work during school hours or scheduling it outside class times could greatly help minimize disruption.
      Thank you for your attention to this important matter. I hope you will take these concerns seriously and
       take appropriate steps
to ensure both safety and consideration for the school community.  Yours sincerely, Name
";

        $question = "You live across from an elementary school where a construction team is working outside. You notice that the construction site does not have a safety fence around it and that it is too noisy during class. You are worried about children getting hurt after school or having difficulty learning in class.
        Describe what you have seen
Explain why you are worried
Suggest how they could work more safely";

        // $answer = $request->answer;

       $result = $answerCheckService->checkWritingAnswer($question, $answer);

dd($result);

        return view('tenant.celpip.writing.result', compact('result'));
    }

// public function submitWriting(Request $request, CelpipAnswerCheckService $answerCheckService)
//     {
//         // Student Answer
//         $answer = "
// The provided column chart illustrates the consumer preferences for various soft drink brands. It is evident from the data that Coca Cola is the most dominant brand in the market, capturing the highest preference at 35%. This is followed by Pepsi, which holds a significant share of 25%, making these two brands the primary leaders in the industry.

// In contrast, other brands show a relatively lower preference among consumers. Sprite stands at 18%, while Diet Coke accounts for 12% of the total share. The least preferred brand according to the chart is Fanta, which sits at only 10%.

// Overall, the chart highlights a clear hierarchy in the soft drink market. There is a substantial 25% gap between the most popular brand, Coca Cola, and the least popular one, Fanta. The data suggests that traditional cola-flavored drinks (Coca Cola and Pepsi) collectively command a massive 60% of the total brand preference, significantly outperforming lemon-lime and orange-flavored alternatives. This indicates a strong consumer loyalty towards the top two global giants in the beverage sector
// ";

//         // Question text
//        $question = "
// Write about 200 words describing and explaining the situation shown in the image.
// Focus only on the information and context presented in the image.
// ";


//         // ✅ IMAGE URL (as requested)
//         $imageUrl = "https://www.slideteam.net/media/catalog/product/cache/1280x720/s/o/soft_drinks_brand_preference_column_chart_slide01.jpg";
//         $result = $answerCheckService->checkWritingAnswer(
//             $question,
//             $answer,
//             $imageUrl
//         );

//         dd($result);

//         return view('tenant.celpip.writing.result', compact('result'));
//     }
}
