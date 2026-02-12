<?php

namespace App\Models\Tenant\Exam;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
     protected $fillable = ['exam_part_id','name','slug','description','sort_order'];

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }
}
