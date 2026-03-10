<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Controller
{
   
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

            //    dd(Auth::user()->id);

        $data = [];
        $todayDate = date('Y-m-d');
        $today['totalSales'] = DB::table('invoice_masters')
            ->where('type','INV')
            ->where('date',$todayDate)
            ->sum('grand_total');
            
        $today['totalOrders'] = DB::table('invoice_masters')
            ->where('type','INV')
            ->where('date',$todayDate)
            ->count();

        $today['AverageOrderValue'] =  $today['totalOrders'] > 0 
            ? number_format($today['totalSales'] / $today['totalOrders'],2)
            : 0;

        $today['totalExpenses'] = DB::table('expenses')->where('date',$todayDate)->sum('grand_total');


        $cashAndBankSummary = $this->cashAndBankSummary();
       

        




        return view('dashboards.admin', compact('data','today','cashAndBankSummary'));
    }


    private function cashAndBankSummary()
    {
        $cashBankCard = DB::table('chart_of_accounts')
            ->select('id','name','category')
            ->whereIn('category',['cash','bank','card'])
            ->get();

        foreach($cashBankCard as $account)
        {
            $cashAndBankSummary[] = [
                'name' => $account->name,
                'balance' => DB::table('journals')
                    ->where('chart_of_account_id', $account->id)
                    ->select(DB::raw('COALESCE(SUM(debit),0) - COALESCE(SUM(credit),0) as balance'))
                    ->value('balance'),
            ];
        }


        return $cashAndBankSummary;
    }
}
