<?php

namespace App\Models;

use App\Models\Recipe;
use App\Models\Startup\Unit;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'product_variation_id',
        'unit_id',
        'quantity',

        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
