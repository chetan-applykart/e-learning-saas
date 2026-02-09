<?php

namespace App\AI\Tools;

use Prism\Prism\Facades\Tool;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Tool as PrismTool;

class LangcertWritingScoreTool
{
    public static function make(): PrismTool
    {
        return Tool::as('langcert_writing_score')
            ->for('Evaluate LANGCERT writing answer and return structured scores and errors')
            ->withParameter(
                new ObjectSchema(
                    name: 'evaluation',
                    description: 'LANGCERT writing evaluation result',
                    properties: [

                        new NumberSchema(
                            'total_score',
                            'Total score from 0 to 36 (sum of all criteria)'
                        ),

                        new NumberSchema(
                            'content_coherence',
                            'Content & Coherence score (0–9)'
                        ),

                        new NumberSchema(
                            'vocabulary_score',
                            'Vocabulary score (0–9)'
                        ),

                        new NumberSchema(
                            'listenability',
                            'Listenability / readability score (0–9)'
                        ),

                        new NumberSchema(
                            'task_fulfillment',
                            'Task Fulfillment score (0–9)'
                        ),

                        new ObjectSchema(
                            name: 'grammar_details',
                            description: 'Separated grammar errors with wrong and correct forms',
                            properties: [

                                new StringSchema(
                                    'spelling_errors',
                                    'Spelling mistakes in format: wrong → correct'
                                ),

                                new StringSchema(
                                    'grammar_errors',
                                    'Grammar mistakes (articles, tense, sentence structure): wrong → correct'
                                ),

                                new StringSchema(
                                    'punctuation_errors',
                                    'Punctuation mistakes (comma, period, apostrophe): wrong → correct'
                                ),

                                new StringSchema(
                                    'capitalization_errors',
                                    'Capitalization mistakes: wrong → correct'
                                ),
                            ]
                        ),

                        new StringSchema(
                            'vocabulary_mistakes',
                            'Incorrect word usage with better alternatives'
                        ),

                        new StringSchema(
                            'overall_feedback',
                            'Overall evaluation in simple English'
                        ),

                        new StringSchema(
                            'suggestions',
                            'Clear and practical suggestions to improve the score'
                        ),
                    ],

                    requiredFields: [
                        'total_score',
                        'content_coherence',
                        'vocabulary_score',
                        'listenability',
                        'task_fulfillment',
                    ]
                )
            )
            ->using(function (array $evaluation): string {
                return 'LANGCERT writing evaluation stored successfully';
            });
    }
}
