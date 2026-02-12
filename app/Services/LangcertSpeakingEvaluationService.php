<?php

namespace App\Services;

use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Media\Audio;
use Prism\Prism\ValueObjects\Media\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use App\AI\Tools\LangcertSpeakingScoreTool;

class LangcertSpeakingEvaluationService
{
    public function evaluate(?string $questionText, string $audioPath, ?string $imagePath = null): array
    {
        $llmProvider = env('ACTIVE_LLM_PROVIDER', 'openai');
        $llmModel    = env('ACTIVE_LLM_MODEL', 'gpt-4o-mini');
        $temperature = (float) env('ACTIVE_LLM_TEMPERATURE', 0.1);

        $sttProvider = env('ACTIVE_STT_PROVIDER', 'openai');
        $sttModel    = env('ACTIVE_STT_MODEL', 'whisper-1');

     
        $stt = Prism::audio()
            ->using($sttProvider, $sttModel)
            ->withInput(Audio::fromLocalPath($audioPath))
            ->asText();

        $transcript = trim($stt->text ?? '');

        if (empty($transcript)) {
            return ['success' => false, 'message' => 'No speech detected.'];
        }


        $prismRequest = Prism::text()
            ->using($llmProvider, $llmModel)
            ->withClientOptions(['temperature' => $temperature])
            ->withTools([LangcertSpeakingScoreTool::make()]);

        $systemRule = <<<RULE
You are a certified LangCert Speaking Examiner.

SCORING RULES (STRICT & OBJECTIVE):
1. If transcript has LESS THAN 40 WORDS → give 0 for all criteria.
2. If answer does NOT mention the main task (advice OR choice OR comparison) → give 0 for Task Fulfilment ONLY, not total.
3. NEVER give total_score = 0 unless transcript is under 40 words.
4. Scores must be consistent:
   total_score = task_fulfilment + coherence + grammar + vocabulary + pronunciation
5. Be CONSISTENT for similar answers.
6. Use examiner-style English only.

QUESTION CONTEXT:
Student must give advice and compare BOTH options.
RULE;


        if ($imagePath && file_exists($imagePath)) {
            $prismRequest->withMessages([
                new UserMessage(
                    "{$systemRule} \nTask: Evaluate based on image. Student Answer: {$transcript}",
                    [Image::fromLocalPath($imagePath)]
                )
            ]);
        } else {
            $prismRequest->withPrompt("{$systemRule}\nQuestion: {$questionText}\nAnswer: {$transcript}");
        }

        $response = $prismRequest->generate();

        $toolCall = $response->toolCalls[0] ?? ($response->steps[0]->toolCalls[0] ?? null);

        if ($toolCall) {
            $data = $toolCall->arguments;

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            $finalEval = $data['evaluation'] ?? $data;

            return [
                'success'    => true,
                'transcript' => $transcript,
                'evaluation' => $finalEval,
            ];
        }

        return ['success' => false, 'message' => 'AI Evaluation failed.'];
    }
}
