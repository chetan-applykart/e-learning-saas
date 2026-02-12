<?php

namespace App\Models\Tenant\Exam;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name','slug','description','status'];

    public function modules()
    {
        return $this->hasMany(ExamModule::class);
    }
}
