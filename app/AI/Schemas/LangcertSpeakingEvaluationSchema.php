<?php

namespace App\AI\Schemas;

use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Schema\NumberSchema;

class LangcertSpeakingEvaluationSchema
{
    public static function schema(): ObjectSchema
    {
        return new ObjectSchema(
            name: 'langcert_speaking_evaluation',
            description: 'LangCert Speaking Evaluation (Total 48 Marks)',
            properties: [

                new NumberSchema('task_fulfilment', '0–16'),
                new NumberSchema('coherence', '0–8'),
                new NumberSchema('grammar', '0–8'),
                new NumberSchema('vocabulary', '0–8'),
                new NumberSchema('pronunciation', '0–8'),
                new NumberSchema('total', '0–48'),

                new StringSchema('suggestion_1', 'Improvement suggestion'),
                new StringSchema('suggestion_2', 'Improvement suggestion'),
                new StringSchema(
                    'suggestion_3',
                    'Improvement suggestion',
                    nullable: true
                ),
            ],
            requiredFields: [
                'task_fulfilment',
                'coherence',
                'grammar',
                'vocabulary',
                'pronunciation',
                'total',
                'suggestion_1',
                'suggestion_2',
            ]
        );
    }
}
