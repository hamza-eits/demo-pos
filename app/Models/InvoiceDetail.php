<?php

namespace App\Models;

use App\Models\Startup\Unit;
use App\Models\InvoiceMaster;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        // Invoice identifiers
        'invoice_master_id',
        'invoice_no',
        'date',
        'type',
        'unit_id',

        // Product details
        'product_id',
        'product_variation_id',
        'selling_price',
        'unit_cost',

        'variation_barcode',
        'purchase_category',
        'stock_barcode',

        'product_type',
        // Serial or batch numbers
        'parent_no',
        'child_no',

        // Pricing and quantity
        'quantity',
        'unit_price',
        'cost_amount',
        'subtotal',
        'stock_quantity',
        
        

        'discount_unit_price',
        'discount_amount',
        'discount_type',
        'discount_value',
        'subtotal_after_discount',
        'tax_type',
        'tax_value',
        'tax_amount',
        'subtotal_after_tax',
        'grand_total',

        // Unit conversion
        'base_unit_value',
        'base_unit_amount',
        'base_unit_qty',

        'child_unit_value',
        'child_unit_amount',
        'child_unit_qty',

        'description'
    ];

    
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function invoiceMaster()
    {
        return $this->belongsTo(InvoiceMaster::class);
    }

    public function scopePosProductTypes($query)
    {
        return $query->whereIn('product_type', ['dish', 'addon','item']);
    }
}
