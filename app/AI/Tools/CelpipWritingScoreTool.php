<?php

namespace App\AI\Tools;

use Prism\Prism\Facades\Tool;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Tool as PrismTool;

class CelpipWritingScoreTool
{
    public static function make(): PrismTool
    {
        return Tool::as('celpip_writing_score')
            ->for('Evaluate CELPIP writing answer and return structured scores and errors')
            ->withParameter(
                new ObjectSchema(
                    name: 'evaluation',
                    description: 'CELPIP writing evaluation result',
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
                            description: 'ALL grammar errors (mandatory)',
                            properties: [

                                new StringSchema(
                                    'spelling_errors',
                                    "ALL spelling mistakes.\nEach on new line.\nFormat: wrong → correct\nWrite 'none' only if zero."
                                ),

                                new StringSchema(
                                    'grammar_errors',
                                    "ALL grammar mistakes (articles, tense, verb forms, prepositions).\nEach on new line.\nFormat: wrong → correct"
                                ),

                                new StringSchema(
                                    'punctuation_errors',
                                    "ALL punctuation mistakes (missing full stop, comma, colon in letters).\nEach on new line.\nFormat: wrong → correct"
                                ),

                                new StringSchema(
                                    'capitalization_errors',
                                    "ALL capitalization mistakes (sentence start, proper nouns, letter format).\nEach on new line.\nFormat: wrong → correct"
                                ),

                                new StringSchema(
                                    'sentence_structure_errors',
                                    "ALL sentence structure errors (run-ons, fragments, word order).\nEach on new line.\nFormat: wrong → correct"
                                ),
                            ],
                            requiredFields: [
                                'spelling_errors',
                                'grammar_errors',
                                'punctuation_errors',
                                'capitalization_errors',
                                'sentence_structure_errors',
                            ]
                        ),

                        new StringSchema('vocabulary_mistakes', 'Word usage improvements'),
                        new StringSchema('overall_feedback', 'Simple English feedback'),
                        new StringSchema('suggestions', 'How to improve CELPIP score'),
                    ],

                    requiredFields: [
                        'total_score',
                        'content_coherence',
                        'vocabulary_score',
                        'listenability',
                        'task_fulfillment',
                        'grammar_details',
                    ]
                )
            )
            ->using(fn () => 'stored');
    }
}
