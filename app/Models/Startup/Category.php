<?php

namespace App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image' ,
        'is_active',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

}
