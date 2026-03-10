<?php

namespace App\Models\Accounting;

use App\Models\Accounting\Journal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $fillable = [

        'id',
        'name',
        'description',
        'level',
        'parent_id',
        'type',
        'category',
        'is_lock',
        'is_active',
        'created_by',
        'updated_by',
        'branch_id',
        'business_id',
    ];

    // Define a relationship for the parent account
    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    // Define a relationship for child accounts
    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function journels()
    {
        return $this->hasMany(Journal::class);
    }

    public function scopeLevelFourAccounts($query)
    {
        return $query->select('id','name','level')->where('level',4)->get();
    }

    public function scopeHavingCategory($query)
    {
        return $query->whereIn('category',['cash','bank']);
    }


    /**
     * Get accounts list based on user type.
     * For 'biller' returns only office expense accounts, for 'admin' returns all accounts.
     */
    public static function getExpenseAccountsByUserType()
    {
        $userType = Auth::user()->type;
        $officeExp = 522000;

        return self::where('level', 4)
            ->where('type','expense')
            ->when($userType == 'user', fn($query) =>$query->where('parent_id', $officeExp))
            ->get(); 
    }


    public static function getPettyCashAccount()
    {
        return self::where('id',111200)->get();
    }



}
