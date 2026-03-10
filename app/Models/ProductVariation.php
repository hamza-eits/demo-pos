<?php

namespace App\Models;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'product_type',

        'product_id',
        'sku',
        'barcode',
        'selling_price',
        'purchase_price',

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function saleInvoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class)->where('type','INV');
    }
    public function purchaseInvoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class)->where('type','PI');
    }

    public static function generateUniqeBarcode()
    {
        do {
            $barcode = random_int(10000000, 99999999);
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }
    /**
     * Retrieve all product variations that has no recipe or inactive recipes 
     */
    public static function getDishWithoutRecipe()
    {
        $product_variation_ids =  Recipe::where('is_active', 1)->pluck('product_variation_id')->toArray();;

        return ProductVariation::where('product_type', 'dish')->whereNotIn('id', $product_variation_ids)->get();
    }
    public static function getDishWithRecipe()
    {
        $product_variation_ids =  Recipe::where('is_active', 1)->pluck('product_variation_id')->toArray();;

        return ProductVariation::where('product_type', 'dish')->whereIn('id', $product_variation_ids)->get();
    }
    public function activeRecipe()
    {
        return $this->hasOne(Recipe::class)->where('is_active', 1);
    }


    public function scopePurchaseInvoice($query)
    {
        return $query->whereIn('product_type',['item'])->orderBy('id','desc');
    }

    public function scopeInventory($query)
    {
        return $query->where('product_type','item');
    }

   
}
