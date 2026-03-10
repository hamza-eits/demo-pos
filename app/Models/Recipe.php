<?php

namespace App\Models;

use App\Models\RecipeDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variation_id',
        'name',
        'start_date',
        'start_time',

        'description',
        'total_quantity',
        'end_date',
        'end_time',
        'is_active',

        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

    public function details()
    {
        return $this->hasMany(RecipeDetail::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
