<?php

namespace App\Services;

use Prism\Prism\Facades\Prism;
use App\AI\Tools\LangcertWritingScoreTool;
use App\Traits\UsesPrismConfig;
use App\Traits\HandlesPrismToolResponse;
use App\Traits\Prompts\LangcertImagePromptTrait;

class LangcertImageCheckService
{
    use UsesPrismConfig, LangcertImagePromptTrait, HandlesPrismToolResponse;


    public static function checkWritingImage(string $question, string $answer, ?string $imageUrl = null): array
    {
        $instance = new self();

        $response = Prism::text()
            ->using($instance->getPrismProvider(), $instance->getPrismModel())
            ->withSystemPrompt($instance->langcertWritingImagePrompt())
            ->withPrompt($instance->formatPrompt($question, $answer, $imageUrl))
            ->withTools([LangcertWritingScoreTool::make()])
            ->usingTemperature(0.1)
            ->asText();

        $evaluation = $instance->extractToolData($response, 'evaluation');

        if (!empty($evaluation['error'])) {
            return [
                'status'  => false,
                'message' => 'LangCert image writing evaluation failed.',
                'error'   => $evaluation
            ];
        }

        return [
            'status'     => true,
            'evaluation' => $evaluation
        ];
    }


    private function formatPrompt(string $question, string $answer, ?string $imageUrl): string
    {
        return collect([
            "Question:\n{$question}",
            $imageUrl ? "Image Context (Mandatory):\n{$imageUrl}" : null,
            "Student Answer:\n{$answer}",
        ])->filter()->implode("\n\n");
    }
}
