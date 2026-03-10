<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [

        'invoice_master_id',
        'mode',
        'number_of_payments',
        'date',
        'time',
        'voucher_no',
        'voucher_id',
        'chart_of_account_id',
        'amount',
        'cash_register_id',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];
    public function invoiceMaster()
    {
        return $this->belongsTo(InvoiceMaster::class);
    }
}
