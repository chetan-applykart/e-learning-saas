<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class FormStructure extends Model
{
    use BelongsToTenant;

    protected $table = 'form_structures';

    protected $fillable = [
        'exam',
        'exam_type',
        'part_name',
        'form_type',
        'form_short_name',
        'description',
        'sort_order',
    ];
}
