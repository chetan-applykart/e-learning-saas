<?php

namespace App\Services;

use App\AI\Tools\CelpipWritingScoreTool;
use Prism\Prism\Facades\Prism;
use App\Traits\UsesPrismConfig;
use App\Traits\HandlesPrismToolResponse;
use App\Traits\Prompts\CelpipSystemPromptTrait;

class CelpipAnswerCheckService
{
    use UsesPrismConfig, CelpipSystemPromptTrait, HandlesPrismToolResponse;

    public static function checkWritingAnswer(string $question, string $answer): array
    {
        $self = new self();

        $response = Prism::text()
            ->using(
                $self->getPrismProvider(),
                $self->getPrismModel()
            )
            ->withSystemPrompt($self->celpipSystemPrompt())
            ->withPrompt("
                        Question:
                        {$question}

                        Student Answer:
                        {$answer}
                        ")
            ->withTools([CelpipWritingScoreTool::make()])
            ->usingTemperature(0.2)
            ->asText();

        $evaluation = $self->extractToolData(
            $response,
            'evaluation'
        );

        if (!empty($evaluation['error'])) {
            return [
                'status' => false,
                'message' => 'Writing evaluation failed.',
                'error'   => $evaluation
            ];
        }

        return [
            'status' => true,
            'evaluation' => $evaluation
        ];
    }
}
