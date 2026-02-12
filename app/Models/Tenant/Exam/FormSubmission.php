<?php

namespace App\Models\Tenant\Exam;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'exam_id',
        'exam_module_id',
        'exam_part_id',
        'form_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
