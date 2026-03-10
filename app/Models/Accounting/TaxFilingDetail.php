<?php

namespace App\Models\Accounting;

use App\Models\Accounting\TaxFiling;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxFilingDetail extends Model
{
    use HasFactory;
    public function chartOfAccount(){
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function taxFiling()
    {
        return $this->belongsTo(TaxFiling::class);

    }
}
