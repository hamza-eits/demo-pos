<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationBarcode extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_master_id',
        'invoice_detail_id',
        'variation_barcode',
        'purchase_category',
        'stock_barcode',
        'barcode',
        'purchase_price',
        'is_printed',
    ];


    public function invoiceMaster()
    {
        return $this->belongsTo(InvoiceMaster::class);
    }
}
