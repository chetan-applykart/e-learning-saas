<?php

namespace App\AI\Tools;

use Prism\Prism\Facades\Tool;
use Prism\Prism\Tool as PrismTool;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Schema\NumberSchema;

class LangcertSpeakingScoreTool
{
    public static function make(): PrismTool
    {
        return Tool::as('langcert_speaking_score')
            ->for('Provide band judgement only (no totals)')
            ->withParameter(
                new ObjectSchema(
                    name: 'evaluation',
                    description: 'Band-based examiner judgement',
                    properties: [
                        new NumberSchema('task_fulfilment', '0–16 band estimate'),
                        new NumberSchema('coherence', '0–8 band estimate'),
                        new NumberSchema('grammar', '0–8 band estimate'),
                        new NumberSchema('vocabulary', '0–8 band estimate'),
                        new NumberSchema('pronunciation', '0–8 band estimate'),
                        new StringSchema('language_issues', 'Key issues'),
                        new StringSchema('overall_feedback', 'Examiner feedback'),
                        new StringSchema('improvement_tips', 'Improvement advice'),
                    ],
                    requiredFields: [
                        'task_fulfilment',
                        'coherence',
                        'grammar',
                        'vocabulary',
                        'pronunciation',
                        'language_issues',
                        'overall_feedback',
                        'improvement_tips'
                    ]
                )
            )
            ->using(fn () => 'langcert-speaking');
    }
}
