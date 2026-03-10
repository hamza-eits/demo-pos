<?php

namespace App\Models\Accounting;

use App\Models\Startup\User;
use App\Models\Startup\Party;
use App\Models\Accounting\Journal;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\ExpenseDetail;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'expense_no',
        'expense_type',
        'party_id',
        'cash_register_id',
        'chart_of_account_id',
        'reference_no',
        'description',
        'total_amount',
        'total_tax_amount',
        'grand_total',
        'attachment',
        'spend_by',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
        'tax_filing_id',
        'is_tax_filed',
    ];

    public function details(){
        return $this->hasMany(ExpenseDetail::class);
    }
    public function journals(){
        return $this->hasMany(Journal::class);
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
    
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Party::class)->where('party_type','supplier');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function spendBy()
    {
        return $this->belongsTo(User::class,'spend_by');
    }

    public static function generateExpenseNo($prefix)
    {
        $latestExpense = self::where('expense_no', 'like', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(expense_no, "-", -1) AS UNSIGNED) DESC')
            ->first();

        if ($latestExpense) {
            $lastNumber = (int) explode('-', $latestExpense->expense_no)[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . $newNumber;
    }
    
}
