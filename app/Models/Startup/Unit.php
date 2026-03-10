<?php

namespace App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'base_unit',
        'base_unit_value',
        'child_unit',
        'child_unit_value',
        'operator',
        'is_active',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

}
