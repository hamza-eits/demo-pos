<?php

namespace App\Models\Accounting;

use App\Models\Accounting\VoucherDetail;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'voucher_no',
        'date',
        'code',
        'type',
        'chart_of_account_id',
        'invoice_master_id',
        'invoice',
        'narration',
        'total_amount',
        'attachment',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];


    public function details()
    {
        return $this->hasMany(VoucherDetail::class);
    }
    public function chartOfAccount(){
        return $this->belongsTo(ChartOfAccount::class);
    }
    /**
     * Scope a query to get the total amount received for a given invoice.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $id The Inovice Master ID.
     * @return float The total amount received for the given invoice.
     */
    public function scopeReceivedAmount($query,$id)
    {
        return $query->where('invoice_master_id',$id)
        ->whereIn('code',['BR','CR'])
        ->sum('total_amount');
    }

    /**
     * Generate a new voucher number based on the given code.
     *
     * @param string $code The code prefix for the voucher (e.g., 'BP BR').
     * @return string The newly generated voucher number (e.g., 'BP BR-10').
     */
    public static function generateVoucherNo(string $code): string
    {
        // Fetch the maximum numeric part of the voucher_no for the given code
        // SUBSTRING_INDEX extracts the numeric part after the last '-'
        // CAST ensures the numeric part is treated as an integer
        // MAX finds the largest existing numeric value, and we add 1 to it
        $max_voucher_no = DB::table('vouchers')
            ->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(voucher_no, '-', -1) AS UNSIGNED)) + 1 as maximum_no"))
            ->where('code', $code) // Filter by the given code
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
