<?php

namespace App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model 
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
