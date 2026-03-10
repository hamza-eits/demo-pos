<?php

namespace App\Models\Accounting;

use App\Models\Startup\Party;
use App\Models\Accounting\Voucher;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'date',
        'voucher_no',
        'code',
        'type',
        'chart_of_account_id',
        'invoice_master_id',

        'party_id',
        'customer_id',
        'supplier_id',
        'narration',
        'reference_no',
        'debit',
        'credit',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

    public function voucher(){
        return $this->belongsTo(Voucher::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Party::class,'customer_id')->where('party_type','customer');
    }
    public function supplier()
    {
        return $this->belongsTo(Party::class,'supplier_id')->where('party_type','supplier');
    }
    

    public function chartOfAccount(){
        return $this->belongsTo(ChartOfAccount::class);
    }
}
