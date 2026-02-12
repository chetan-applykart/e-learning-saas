<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Tenant\Exam\Exam as TenantExam;
use App\Models\Tenant\Exam\ExamModule;
use App\Models\Tenant\Exam\ExamPart;
use App\Models\Tenant\Exam\Form;
use App\Models\Tenant\Exam\FormField;

class TenantCelpipSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Create CELPIP Exam
        |--------------------------------------------------------------------------
        */

        $exam = TenantExam::firstOrCreate(
            ['slug' => 'celpip'],
            [
                'name' => 'CELPIP',
                'description' => 'CELPIP Official Structure',
                'status' => 1
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. Create Modules
        |--------------------------------------------------------------------------
        */

        $modules = [
            'listening',
            'reading',
            'writing',
            'speaking'
        ];

        foreach ($modules as $moduleSlug) {

            $module = ExamModule::firstOrCreate(
                [
                    'exam_id' => $exam->id,
                    'slug' => $moduleSlug
                ],
                [
                    'name' => ucfirst($moduleSlug),
                    'sort_order' => 1
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 3. Listening Specific Parts
            |--------------------------------------------------------------------------
            */

            if ($moduleSlug === 'listening') {

                $listeningParts = [
                    'Part 1',
                    'Part 2',
                    'Part 3',
                    'Part 4'
                ];

                foreach ($listeningParts as $index => $partName) {

                    $part = ExamPart::firstOrCreate(
                        [
                            'exam_module_id' => $module->id,
                            'name' => $partName
                        ],
                        [
                            'instructions' => 'Default instructions for ' . $partName,
                            'sort_order' => $index + 1
                        ]
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | 4. Forms
                    |--------------------------------------------------------------------------
                    */

                    $forms = [
                        ['name' => 'Single Choice', 'slug' => 'l_mcs'],
                        ['name' => 'Multiple Choice', 'slug' => 'l_mcm'],
                        ['name' => 'HIWS', 'slug' => 'hiws'],
                    ];

                    foreach ($forms as $formData) {

                        $form = Form::firstOrCreate(
                            [
                                'exam_part_id' => $part->id,
                                'slug' => $formData['slug']
                            ],
                            [
                                'name' => $formData['name'],
                                'description' => $formData['name'] . ' form',
                                'sort_order' => 1
                            ]
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | 5. Default Fields
                        |--------------------------------------------------------------------------
                        */

                        // Title
                        FormField::firstOrCreate(
                            [
                                'form_id' => $form->id,
                                'name' => 'title'
                            ],
                            [
                                'label' => 'Title',
                                'type' => 'text',
                                'required' => true,
                                'sort_order' => 1
                            ]
                        );

                        // Question
                        FormField::firstOrCreate(
                            [
                                'form_id' => $form->id,
                                'name' => 'question'
                            ],
                            [
                                'label' => 'Question',
                                'type' => 'textarea',
                                'required' => true,
                                'sort_order' => 2
                            ]
                        );

                        // Audio
                        FormField::firstOrCreate(
                            [
                                'form_id' => $form->id,
                                'name' => 'audio'
                            ],
                            [
                                'label' => 'Audio File',
                                'type' => 'file',
                                'required' => false,
                                'sort_order' => 3
                            ]
                        );

                        // Options (only for MCQ types)
                        if (in_array($formData['slug'], ['l_mcs','l_mcm'])) {

                            FormField::firstOrCreate(
                                [
                                    'form_id' => $form->id,
                                    'name' => 'options'
                                ],
                                [
                                    'label' => 'Options',
                                    'type' => $formData['slug'] === 'l_mcs' ? 'mcq' : 'checkbox',
                                    'options' => ['A','B','C','D'],
                                    'required' => true,
                                    'sort_order' => 4
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
