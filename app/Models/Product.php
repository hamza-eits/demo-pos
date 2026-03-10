<?php

namespace App\Models;

use App\Models\Material;
use App\Models\Startup\Brand;
use App\Models\ProductVariation;
use App\Models\Startup\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable =  [
        'name',
        'product_type',
        'variation_type',


        'category_id',
        'brand_id',
        'unit_id',


        'product_group_id',
        'material_id',
        'custom_field_id',
        'product_model_id',

        'description',

        'image',

        'check_stock_qty',
        'is_price_editable',
        'is_decimal_qty_allowed',
        'is_featured',
        'is_active',

        'created_by',
        'updated_by',
        'branch_id',
        'business_id'
    ];

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function productModel()
    {
        return $this->belongsTo(ProductModel::class);
    }
    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }


    public static function getPosProducts()
    {
        return self::whereIn('product_type',['dish','item'])->get();
    }


    public function scopePosProducts(Builder $query)
    {
        return $query->whereIn('product_type',['dish','item']);
    }

    /**
     * Filter by category and brand.
     * If both are missing, limit the results to 30 to reduce load.
     */
    public function scopeApplyPosFilters(Builder $query, array $filters): Builder
    {
        $categoryId = $filters['category_id'] ?? null;
        $brandId    = $filters['brand_id'] ?? null;

        return $query
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($brandId, fn($q) => $q->where('brand_id', $brandId))
            ->when(!$categoryId && !$brandId, fn($q) => $q->take(30));
    }


    public function scopeInventory($query)
    {
        $query->where('product_type','item');
    }   
}
