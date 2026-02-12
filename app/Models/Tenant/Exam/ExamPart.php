<?php

namespace App\Models\Tenant\Exam;

use Illuminate\Database\Eloquent\Model;

class ExamPart extends Model
{
    protected $fillable = ['exam_module_id','name','instructions','sort_order'];

    public function forms()
    {
        return $this->hasMany(Form::class);
    }
}
