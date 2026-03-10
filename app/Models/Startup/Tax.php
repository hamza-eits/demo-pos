<?php

namespace App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'rate' ,
        'is_active',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

}
