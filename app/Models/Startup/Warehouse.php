<?php

namespace App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'is_active',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];
}
