<?php

namespace App\Services;

use App\AI\Tools\CelpipWritingScoreTool;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Text\Response;

class CelpipAnswerCheckService
{
    public function checkWritingAnswer(string $question, string $answer): array
    {
        $providerName = config('services.prism.provider', env('ACTIVE_LLM_PROVIDER'));
        $modelName = config('services.prism.model', env('ACTIVE_LLM_MODEL'));
        $systemPrompt = "
You are a certified CELPIP Writing Examiner.

SCORING RULES (MANDATORY):
- Scores MUST reflect CELPIP standards.
- Grammar mistakes do NOT automatically mean score = 0.
- Use the full range 1–9 for each criterion.
- Average responses with errors typically score between 5–7.
- Only give score 0 if the response is blank or completely irrelevant (THIS RESPONSE IS NOT BLANK).

EVALUATION RULES:
- Identify ALL grammar, spelling, punctuation, capitalization, and sentence structure errors.
- Each error MUST be shown as: wrong → correct
- Use multiple lines for multiple errors.
- Do NOT silently rewrite.

CRITERIA DEFINITIONS:
- Content & Coherence: clarity, logical flow.
- Vocabulary: range and appropriateness.
- Listenability: readability and sentence flow.
- Task Fulfillment: addresses all task points.

You MUST return the result ONLY by calling the tool `celpip_writing_score`.
";



        $response = Prism::text()
            ->using($providerName, $modelName)
            ->withSystemPrompt($systemPrompt)
            ->withPrompt("
Question:
{$question}

Student Answer:
{$answer}
")
            ->withTools([CelpipWritingScoreTool::make()])
            ->usingTemperature(0.2)
            ->asText();

        if (!empty($response->toolCalls)) {
            return json_decode(
                $response->toolCalls[0]->arguments,
                true
            )['evaluation'];
        }

        return [];
    }
}
