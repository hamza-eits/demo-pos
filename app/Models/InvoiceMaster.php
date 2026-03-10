<?php

namespace App\Models;

use App\Models\Recipe;
use App\Models\Startup\User;
use App\Models\InvoiceDetail;
use App\Models\Startup\Party;
use App\Models\Accounting\Journal;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        // Invoice details
        'invoice_no',
        'reference_no',
        'subject',
        'date',
        'due_date',
        'status',
        'payment_mode',
        'type',

        // Party and personnel
        'party_id',
        'biller_id',
        'waiter_id',
        'saleman_id',

        // Item and order totals
        'total_quantity',
        'total_cost_amount',
        'subtotal_items',
        'subtotal_addons',
        'subtotal_before_discount',
        'discount_type',
        'discount_value',
        'discount_amount',
        'subtotal_after_discount',
        'tax_type',
        'tax_value',
        'tax_amount',
        'subtotal_after_tax',
        'tip_amount',
        'shipping_fee',
        'grand_total',

        // Order and table info
        'serving_type',
        'table_no',
        'description',

        // Delivery details
        'rider_name',
        'shipping_address',
        'customer_phone',

        'is_locked',

        // Meta
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];


    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function details(){
        return $this->hasMany(InvoiceDetail::class);
    }

    public function journals(){
        return $this->hasMany(Journal::class);
    }

   
   
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
    public function saleman()
    {
        // Define a relationship indicating that this model belongs to a User
        //using 'saleman_id' as the foreign key referencing the User's 'id'.
        return $this->belongsTo(User::class,'saleman_id');
    }
    public function biller()
    {
        // Define a relationship indicating that this model belongs to a User
        //using 'saleman_id' as the foreign key referencing the User's 'id'.
        return $this->belongsTo(User::class,'biller_id');
    }
    public function waiter()
    {
        // Define a relationship indicating that this model belongs to a User
        //using 'saleman_id' as the foreign key referencing the User's 'id'.
        return $this->belongsTo(User::class,'waiter_id');
    }


    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }
    /**
     * Get a formatted string of payment mode names from the provided payments data.
     *
     * This function accepts a collection or array of payment objects, each containing a `mode` field.
     * It returns a pipe-separated list of all non-empty payment modes. If no payments are available,
     * or if the number of payments is less than one, it returns the mode of the first payment (if any).
     */
    public static function paymentModeNames($data)
    {
        $count = $data[0]->number_of_payments;

        if (!$data) {
            return '';
        }

        if ($count < 1) {
            return $data[0]->mode ?? '';
        }

        $modes = [];

        foreach ($data as $row) {
            $modes[] = $row->mode ?? '';
        }

        return implode(' | ', array_filter($modes));
    }

    // $newInvoiceNo = InvoiceMaster::generateInvoiceNo('PRO','production');
    public static function generateInvoiceNo($type)
    {
        // Fetch the maximum numeric part of the invoice_no, increment it by 1
        // SUBSTRING_INDEX extracts the numeric part after the last '-'
        // CAST converts it to an integer for comparison
        $max_invoice_no = DB::table('invoice_masters')
            ->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(invoice_no, '-', -1) AS UNSIGNED) + 1) as maximum"))
            ->where('type', $type) // Filter by type (e.g., 'receipt')
            ->value('maximum');
    
        // Check if there is an existing invoice number
        if ($max_invoice_no != null) {
            // If a maximum exists, create the new invoice number by appending the incremented value to the type
            $new_invoice_no = $type . '-' . $max_invoice_no;
            return $new_invoice_no;
        } else {
            // If no existing invoice, create the first invoice number with the type and start from '1'
            $new_invoice_no = $type . '-' . '1';
            return $new_invoice_no;
        }
    }


    public function scopeOfTypeInvoice($query)
    {
        return $query->where('type', 'INV');
    }
    public function scopeOfTypePurchase($query)
    {
        return $query->where('type', 'PI');
    }

    
    
}
