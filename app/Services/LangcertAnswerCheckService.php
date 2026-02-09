<?php

namespace App\Services;

use App\AI\Tools\LangcertWritingScoreTool;
use Prism\Prism\Facades\Prism;

class LangcertAnswerCheckService
{
    public function checkWritingAnswer(
        string $question,
        string $answer,
        ?string $imageUrl = null
    ): array {
        $providerName = config('services.prism.provider', env('ACTIVE_LLM_PROVIDER'));
        $modelName    = config('services.prism.model', env('ACTIVE_LLM_MODEL'));

        $systemPrompt = <<<PROMPT
You are a professional LANGCERT writing examiner. Your evaluation must be extremely strict regarding task relevance.

### MANDATORY PENALTY RULES:
- **Off-Topic / Irrelevant:** If the student's answer is not directly related to the provided "Question" or "Image description", you MUST assign a score of **0** to ALL categories: task_achievement, accuracy_grammar, accuracy_vocab, and organisation.
- **Image Mismatch:** If an image context is provided and the student's answer ignores it or describes something else, all scores MUST be **0**.
- **No Partial Credit for Irrelevance:** Even if the grammar and vocabulary are perfect, if the topic is wrong, the total score is **0**.

### LANGCERT Scoring Criteria:
1. task_achievement (0–8)
2. accuracy_grammar (0–8)
3. accuracy_vocab (0–8)
4. organisation (0–8)

### Operational Instructions:
- Detect errors (spelling, grammar, punctuation) only if the answer is relevant.
- If irrelevant, state in the feedback: "The answer is completely off-topic or does not match the provided image/task."
- DO NOT return any conversational text.
- ONLY execute the `langcert_writing_score` tool.
PROMPT;

        $prompt = "### TASK/QUESTION:\n{$question}\n\n";

        if ($imageUrl) {
            $prompt .= "### MANDATORY IMAGE CONTEXT:\n";
            $prompt .= "{$imageUrl}\n\n";
        }

        $prompt .= "### STUDENT ANSWER TO EVALUATE:\n{$answer}";

        $response = Prism::text()
            ->using($providerName, $modelName)
            ->withSystemPrompt($systemPrompt)
            ->withPrompt($prompt)
            ->withTools([LangcertWritingScoreTool::make()])
            ->usingTemperature(0.1)
            ->asText();

        if (!empty($response->toolCalls)) {
            $arguments = json_decode($response->toolCalls[0]->arguments, true);
            return $arguments['evaluation'] ?? $arguments ?? [];
        }

        return [];
    }
}
