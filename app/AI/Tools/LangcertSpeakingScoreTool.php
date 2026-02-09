<?php

namespace App\AI\Tools;

use Prism\Prism\Facades\Tool;
use Prism\Prism\Tool as PrismTool;
use App\AI\Schemas\LangcertSpeakingEvaluationSchema;

class LangcertSpeakingScoreTool
{
    public static function make(): PrismTool
    {
        return Tool::as('langcert_speaking_score')
            ->for('Evaluate LangCert speaking test and return structured scores')
            ->withSchema(
                LangcertSpeakingEvaluationSchema::schema()
            );
    }
}
