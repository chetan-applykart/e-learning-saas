<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Exams Table
        |--------------------------------------------------------------------------
        */
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // CELPIP, IELTS
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Exam Modules (Listening, Reading, etc.)
        |--------------------------------------------------------------------------
        */
        Schema::create('exam_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->string('name'); // Listening
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();

            $table->unique(['exam_id', 'slug']);
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Exam Parts (Part 1, Part 2)
        |--------------------------------------------------------------------------
        */
        Schema::create('exam_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_module_id')
                ->constrained('exam_modules')
                ->cascadeOnDelete();

            $table->string('name'); // Part 1
            $table->text('instructions')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Forms (Question Type)
        |--------------------------------------------------------------------------
        */
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_part_id')
                ->constrained('exam_parts')
                ->cascadeOnDelete();

            $table->string('name'); // Multiple Choice
            $table->string('slug');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Form Fields (Dynamic Inputs)
        |--------------------------------------------------------------------------
        */
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')
                ->constrained('forms')
                ->cascadeOnDelete();

            $table->string('label'); // Title, Audio
            $table->string('name');  // title, audio_file
            $table->string('type');  // text, textarea, file, mcq, checkbox
            $table->boolean('required')->default(false);

            $table->json('options')->nullable(); // MCQ options

            $table->string('placeholder')->nullable();
            $table->string('validation_rules')->nullable();

            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('exam_parts');
        Schema::dropIfExists('exam_modules');
        Schema::dropIfExists('exams');
    }
};
