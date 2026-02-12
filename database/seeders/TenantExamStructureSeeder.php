<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Tenant\Exam\Exam as TenantExam;
use App\Models\Tenant\Exam\ExamModule;
use App\Models\Tenant\Exam\ExamPart;
use App\Models\Tenant\Exam\Form;
use App\Models\Tenant\Exam\FormField;

class TenantExamStructureSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Create Exam
        |--------------------------------------------------------------------------
        */

        $exam = TenantExam::create([
            'name' => 'Demo Exam',
            'slug' => Str::slug('Demo Exam'),
            'description' => 'Auto generated exam structure',
            'status' => 1
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. Default Modules
        |--------------------------------------------------------------------------
        */

        $modules = ['Listening', 'Reading', 'Writing', 'Speaking'];

        foreach ($modules as $moduleName) {

            $module = ExamModule::create([
                'exam_id' => $exam->id,
                'name' => $moduleName,
                'slug' => Str::slug($moduleName),
                'sort_order' => 1
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3. Default Parts
            |--------------------------------------------------------------------------
            */

            for ($i = 1; $i <= 2; $i++) {

                $part = ExamPart::create([
                    'exam_module_id' => $module->id,
                    'name' => 'Part ' . $i,
                    'instructions' => 'Default instructions for Part ' . $i,
                    'sort_order' => $i
                ]);

                /*
                |--------------------------------------------------------------------------
                | 4. Default Form
                |--------------------------------------------------------------------------
                */

                $form = Form::create([
                    'exam_part_id' => $part->id,
                    'name' => 'Multiple Choice',
                    'slug' => 'multiple-choice',
                    'description' => 'Auto generated MCQ form',
                    'sort_order' => 1
                ]);

                /*
                |--------------------------------------------------------------------------
                | 5. Default Fields
                |--------------------------------------------------------------------------
                */

                FormField::create([
                    'form_id' => $form->id,
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => true,
                    'sort_order' => 1
                ]);

                FormField::create([
                    'form_id' => $form->id,
                    'label' => 'Question',
                    'name' => 'question',
                    'type' => 'textarea',
                    'required' => true,
                    'sort_order' => 2
                ]);

                FormField::create([
                    'form_id' => $form->id,
                    'label' => 'Audio File',
                    'name' => 'audio',
                    'type' => 'file',
                    'required' => false,
                    'sort_order' => 3
                ]);

                FormField::create([
                    'form_id' => $form->id,
                    'label' => 'Options',
                    'name' => 'options',
                    'type' => 'mcq',
                    'required' => true,
                    'options' => ['A', 'B', 'C', 'D'],
                    'sort_order' => 4
                ]);
            }
        }
    }
}
