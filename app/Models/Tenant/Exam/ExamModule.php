<?php

namespace App\Models\Tenant\Exam;

use Illuminate\Database\Eloquent\Model;

class ExamModule extends Model
{
    protected $fillable = ['exam_id','name','slug','sort_order'];

    public function parts()
    {
        return $this->hasMany(ExamPart::class);
    }
}
