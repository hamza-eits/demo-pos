<?php

namespace App\Http\Controllers\Accounting;


use Illuminate\Http\Request;
use App\Models\Accounting\Journal;
use App\Models\Accounting\ChartOfAccount;
use App\Http\Controllers\Controller; //import base controller

class PnlController extends Controller
{
    public function show(Request $request)
    {
        
        $date = $request->date ?? today('Y-m-d');


        $expense_level_one = ChartOfAccount::where('level', 1)
        ->where('type', 'expense')
        ->first();

       
        $level_two = ChartOfAccount::where('parent_id', $expense_level_one->id)->get();

        $topData = [
            'first' => $expense_level_one->name,
            'second' => []
        ];

        foreach ($level_two as $account) {
            $level_three = ChartOfAccount::where('parent_id', $account->id)->get();

            $secondData = [
                'second' => $account->name,
                'third' => []
            ];
            
            $sum_of_level_3 = 0;
            foreach ($level_three as $account) {
                $level_four = ChartOfAccount::where('parent_id', $account->id)->get();

                $thirdData = [
                    'third' => $account->name,
                    'fourth' => [] // Initialize fourth level
                ];

                $sum_of_level_4 = 0;

                
                foreach ($level_four as $account) {

                    $totalDebit = Journal::where('chart_of_account_id', $account->id)->where('date','<=',$date)->sum('debit');
                    $totalCredit = Journal::where('chart_of_account_id', $account->id)->where('date','<=',$date)->sum('credit');
                    $balance = $totalDebit - $totalCredit;

                    if($balance != 0){
                        $thirdData['fourth'][] = [
                            'name' => $account->name,
                            'total_debit' => $totalDebit,
                            'total_credit' => $totalCredit,
                        ];
                    }
                
                    $sum_of_level_4 += $balance;       
                }
                
                if($sum_of_level_4 != 0){
                    $secondData['third'][] = $thirdData;
                }
                $sum_of_level_3 += $sum_of_level_4;

            }
            if($sum_of_level_3 != 0){
                $topData['second'][] = $secondData;
            }
        }

        $expenseData[] = $topData;





        $revenue_level_one = ChartOfAccount::where('level', 1)
        ->where('type', 'revenue')
        ->first();

       
        $level_two = ChartOfAccount::where('parent_id', $revenue_level_one->id)->get();

        $topData = [
            'first' => $revenue_level_one->name,
            'second' => []
        ];

        foreach ($level_two as $account) {
            $level_three = ChartOfAccount::where('parent_id', $account->id)->get();

            $secondData = [
                'second' => $account->name,
                'third' => []
            ];
            
            $sum_of_level_3 = 0;
            foreach ($level_three as $account) {
                $level_four = ChartOfAccount::where('parent_id', $account->id)->get();

                $thirdData = [
                    'third' => $account->name,
                    'fourth' => [] // Initialize fourth level
                ];

                $sum_of_level_4 = 0;

                
                foreach ($level_four as $account) {

                    $totalDebit = Journal::where('chart_of_account_id', $account->id)->where('date','<=',$date)->sum('debit');
                    $totalCredit = Journal::where('chart_of_account_id', $account->id)->where('date','<=',$date)->sum('credit');
                    $balance = $totalCredit - $totalDebit;

                    if($balance != 0){
                        $thirdData['fourth'][] = [
                            'name' => $account->name,
                            'total_debit' => $totalDebit,
                            'total_credit' => $totalCredit,
                        ];
                    }
                
                    $sum_of_level_4 += $balance;       
                }
                
                if($sum_of_level_4 != 0){
                    $secondData['third'][] = $thirdData;
                }
                $sum_of_level_3 += $sum_of_level_4;

            }
            if($sum_of_level_3 != 0){
                $topData['second'][] = $secondData;
            }
        }

        $revenueData[] = $topData;

        // return response()->json($revenueData[0]['second']);
        return view('accounting.pnl.show', compact('expenseData','revenueData'));

    }


    public function request()
    {
        return view('accounting.pnl.request');
    }
  

}
