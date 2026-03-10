<?php

namespace App\Http\Controllers\Accounting;


use Illuminate\Http\Request;
use App\Models\Startup\Party;
use App\Models\Accounting\Journal;
use App\Models\Accounting\ChartOfAccount;
use App\Http\Controllers\Controller; //import base controller

class BalanceSheetController extends Controller
{  
    /*
        public function show(Request $request)
        {
            
            $date = $request->date ?? today('Y-m-d');


            $level_one = ChartOfAccount::where('level', 1)
            ->whereIn('type', ['asset', 'liability', 'equity'])
            ->get();

            foreach ($level_one as $account) {
                $level_two = ChartOfAccount::where('parent_id', $account->id)->get();

                $topData = [
                    'first' => $account->name,
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
                                    'balance' => $totalDebit - $totalCredit
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

                $data[] = $topData;
            }




            $leftLoop = $data[0];

            $rightLoop =array_slice($data, 1);
            
            $expense_coa = ChartOfAccount::where('level', 4)
            ->where('type', 'expense')
            ->get()
            ->pluck('id');

            $exp_totalDebit = Journal::whereIn('chart_of_account_id', $expense_coa)->where('date','<=',$date)->sum('debit');
            $exp_totalCredit = Journal::whereIn('chart_of_account_id', $expense_coa)->where('date','<=',$date)->sum('credit');
            $exp_blance = $exp_totalDebit - $exp_totalCredit;



            $revenue_coa = ChartOfAccount::where('level', 4)
            ->where('type', 'revenue')
            ->get()
            ->pluck('id');

            $rev_totalDebit = Journal::whereIn('chart_of_account_id', $revenue_coa)->where('date','<=',$date)->sum('debit');
            $rev_totalCredit = Journal::whereIn('chart_of_account_id', $revenue_coa)->where('date','<=',$date)->sum('credit');
            $rev_blance = $rev_totalCredit - $rev_totalDebit;

            $profit = $rev_blance - $exp_blance;
            
            // dd($profit);

            // return response()->json([$leftLoop]);

            return view('accounting.balance_sheet.show', compact('leftLoop', 'rightLoop','profit'));

            // $pdf = PDF::loadView('accounting.balance_sheet.pdf', compact('leftLoop', 'rightLoop','profit'));  
            // $pdf->setPaper('A4', 'landscape');
            // return $pdf->stream();
        }

    */


    public function show(Request $request)
    {
        
        $startDate = $request->startDate ?? today()->format('Y-m-d');
        $endDate = $request->endDate ?? today()->format('Y-m-d');
        
        $date = $request->date ?? today()->format('Y-m-d');

        $assetData = $this->buildAccountData('asset', $date);
        $liabilityData = $this->buildAccountData('liability', $date);
        $equityData = $this->buildAccountData('equity', $date);



        return view('accounting.balance_sheet.show', compact('assetData', 'liabilityData','equityData'));
    }

    /**
     * Build data for a given account type (expense or revenue)
     *
     * @param string $type
     * @param string $date
     * @return array
     */
    private function buildAccountData(string $type, string $date): array
    {
        $accounts = ChartOfAccount::where('level', 3)
            ->where('type', $type)
            ->get();

        $data = [];

        foreach ($accounts as $account) {
            $level3Name = $account->name;
            $level4Accounts = ChartOfAccount::where('parent_id', $account->id)->get();

            $level4 = [];
            $level3Balance = 0;

            foreach ($level4Accounts as $childAccount) {
                [$debit, $credit] = $this->getAccountSums($childAccount->id, $date);
                $balance = $type === 'asset'
                    ? $debit - $credit
                    : $credit - $debit;

                $level3Balance += $balance;

                if ($balance != 0) {
                    $level4[] = [
                        'name'   => $childAccount->name,
                        'debit'  => $debit,
                        'credit' =>$credit,
                    ];
                }
            }

            if ($level3Balance != 0) {
                $data[] = [
                    'level3Name'    => $level3Name,
                    'level3Balance' => $level3Balance,
                    'level4'        => $level4
                ];
            }
        }

        return $data;
    }

    /**
     * Get total debit and credit for a given account up to a date
     *
     * @param int $accountId
     * @param string $date
     * @return array [debit, credit]
     */
    private function getAccountSums(int $accountId, string $date): array
    {
        $debit = Journal::where('chart_of_account_id', $accountId)
            ->where('date', '<=', $date)
            ->sum('debit');

        $credit = Journal::where('chart_of_account_id', $accountId)
            ->where('date', '<=', $date)
            ->sum('credit');

        return [$debit, $credit];
    }

    public function request()
    {
        return view('accounting.balance_sheet.request');
    }
  

}
