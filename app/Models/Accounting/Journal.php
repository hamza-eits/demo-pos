<?php

namespace App\Models\Accounting;

use App\Models\Startup\Party;
use App\Models\Accounting\Voucher;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;
    
    public function getDateAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    protected $fillable = [
        'date',
        'voucher_no',
        'code',
        'type',
        'chart_of_account_id',
        'narration',

        'party_id',
        'customer_id',
        'supplier_id',

        'voucher_id',
        'invoice_master_id',
        'production_id',
        'expense_id',
        'tax_filing_id',

        'debit',
        'credit',
        
        'trace',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

    


    public function party()
    {
        return $this->belongsTo(Party::class);
    }
    public function customer()
    {
        return $this->belongsTo(Party::class)->where('party_type','customer');
    }
    public function supplier()
    {
        return $this->belongsTo(Party::class)->where('party_type','supplier');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
    

   
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);

    }

    public static function currentAssetAccounts()
    {
        $data = ['111100','111200'];

        return $data;
    }



}
