<?php

namespace App\Models\Accounting;

use App\Models\Accounting\Expense;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'date',
        'expense_no',
        'chart_of_account_id',
        'description',
        'amount',
        'tax_percentage',
        'tax_type',
        'tax_amount',
        'total',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

    public function expense(){
        return $this->belongsTo(Expense::class);
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
}
