<?php

namespace App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'email',
        'address',
        'contact_no',
        'whatsapp_no',
        'trn_no',
        'website',
        'logo',
    ];
}
