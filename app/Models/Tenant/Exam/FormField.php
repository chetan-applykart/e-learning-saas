<?php

namespace App\Models\Tenant\Exam;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
     protected $fillable = [
        'form_id',
        'label',
        'name',
        'type',
        'required',
        'options',
        'placeholder',
        'validation_rules',
        'sort_order'
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
