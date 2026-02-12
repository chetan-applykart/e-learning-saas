<?php

namespace App\Services;

use Prism\Prism\Facades\Prism;
use App\AI\Tools\LangcertWritingScoreTool;
use App\Traits\UsesPrismConfig;
use App\Traits\HandlesPrismToolResponse;
use App\Traits\Prompts\LangcertSystemPromptTrait;

class LangcertAnswerCheckService
{
    use UsesPrismConfig, LangcertSystemPromptTrait, HandlesPrismToolResponse;

    public static function checkWritingAnswer(string $question, string $answer): array
    {
        $self = new self();

        $response = Prism::text()
            ->using(
                $self->getPrismProvider(),
                $self->getPrismModel()
            )
            ->withSystemPrompt($self->langcertWritingSystemPrompt())
            ->withPrompt("
                        Question:
                        {$question}

                        Student Answer:
                        {$answer}
                        ")
            ->withTools([LangcertWritingScoreTool::make()])
            ->usingTemperature(0.1)
            ->asText();

        $evaluation = $self->extractToolData(
            $response,
            'evaluation'
        );

        if (!empty($evaluation['error'])) {
            return [
                'status' => false,
                'message' => 'LangCert writing evaluation failed.',
                'error'   => $evaluation
            ];
        }

        return [
            'status' => true,
            'evaluation' => $evaluation
        ];
    }
}
