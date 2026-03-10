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
        $startDate = $request->startDate ?? today()->format('Y-m-d');
        $endDate = $request->endDate ?? today()->format('Y-m-d');

        $expenseData = $this->buildAccountData('expense', $endDate);
        $revenueData = $this->buildAccountData('revenue', $endDate);



        return view('accounting.pnl.show', compact('expenseData', 'revenueData'));
    }

    /**
     * Build data for a given account type (expense or revenue)
     *
     * @param string $type
     * @param string $date
     * @return array
     */
    private function buildAccountData(string $type, string $endDate): array
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
                [$debit, $credit] = $this->getAccountSums($childAccount->id, $endDate);
                $balance = $type === 'expense'
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
        return view('accounting.pnl.request');
    }
  

}
