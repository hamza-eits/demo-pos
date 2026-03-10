<?php

namespace App\Models\Startup;

use App\Models\PartyWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_type',
        'type',
        'business_name',
        'contact_person',
        'prefix',
        'first_name',
        'middle_name',
        'last_name',
        'mobile',
        'alternate_number',
        'landline',
        'email',
        'tax_number',
        'opening_balance',
        'pay_term_type',
        'credit_limit',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'zip_code',
        'shipping_address',
        'is_active',
        'is_default',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

   
    // public function partyWarehouses()
    // {
    //     return $this->hasMany(PartyWarehouse::class);
    // }
    
}
