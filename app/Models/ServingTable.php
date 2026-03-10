<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServingTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'seating_capacity',
        'location',
        'status',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id'
    ];
}
