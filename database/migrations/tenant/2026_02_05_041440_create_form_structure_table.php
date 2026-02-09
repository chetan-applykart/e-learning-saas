<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_structures', function (Blueprint $table) {
            $table->id();

            // Exam name (CELPIP / IELTS future-ready)
            $table->string('exam', 50)->default('CELPIP');

            // listening / reading / writing / speaking
            $table->string('exam_type', 50);

            // Listening Part 1, Part 2
            $table->string('part_name', 100);

            // Multiple Choice (Multiple)
            $table->string('form_type', 150);

            // l_mcm, l_sstss, u_p
            $table->string('form_short_name', 50);

            // Optional description
            $table->text('description')->nullable();

            // UI ordering
            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            // Prevent duplicates per tenant
            $table->unique(
                ['exam', 'exam_type', 'part_name', 'form_short_name'],
                'tenant_form_structure_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_structures');
    }
};
