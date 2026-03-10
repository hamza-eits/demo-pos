<?php

namespace App\Models\Accounting;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\TaxFilingDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxFiling extends Model
{
    use HasFactory;
    protected $fillable = [
        'voucher_no',
        'start_month_year',
        'end_month_year',
        'filed_on',
        'total_payable',
        'is_posted',
        'is_voucher_created',
        'sales_tax_amount',
        'purchases_tax_amount',
        'expenses_tax_amount',
        'input_tax',
        'output_tax',
        'total_payable'
    ];


    public function details()
    {
        return $this->hasMany(TaxFilingDetail::class);
    }
    
    

    public static function generateVoucherNo(string $code): string
    {
        // Fetch the maximum numeric part of the voucher_no for the given code
        // SUBSTRING_INDEX extracts the numeric part after the last '-'
        // CAST ensures the numeric part is treated as an integer
        // MAX finds the largest existing numeric value, and we add 1 to it
        $max_voucher_no = DB::table('tax_filings')
            ->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(voucher_no, '-', -1) AS UNSIGNED)) + 1 as maximum_no"))
            ->value('maximum_no'); // Retrieve only the 'maximum_no' value directly

        // If a maximum voucher number exists, use it to generate the new voucher number
        if ($max_voucher_no != null) {
            $voucher_no = $code . '-' . $max_voucher_no;
            return $voucher_no;
        } else {
            // If no voucher exists for the given code, start with the first voucher number
            $voucher_no = $code . '-' . '1';
            return $voucher_no;
        }
    }
}
