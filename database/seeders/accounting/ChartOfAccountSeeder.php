<?php

namespace Database\Seeders\Accounting;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         //100000
       // Main asset account
       $assets = DB::table('chart_of_accounts')->insertGetId(['id' => '100000', 'name' => 'Assets', 'level' => 1, 'type' => 'asset']);

       // Subcategory under Assets
       $currentAssets = DB::table('chart_of_accounts')->insertGetId(['id' => '110000', 'name' => 'Current Assets', 'level' => 2, 'parent_id' => $assets, 'type' => 'asset']);

            // Subcategories under Current Assets
            $cash = DB::table('chart_of_accounts')->insertGetId(['id' => '111000', 'name' => 'Cash', 'level' => 3, 'parent_id' => $currentAssets, 'type' => 'asset']);
       
            // Cash subaccounts
            DB::table('chart_of_accounts')->insert([
                ['id' => '111100', 'name' => 'Cash in Hand', 'level' => 4, 'parent_id' => $cash, 'type' => 'asset'],
                ['id' => '111200', 'name' => 'Petty Cash', 'level' => 4, 'parent_id' => $cash, 'type' => 'asset']
            ]);

       // Accounts Receivable
       $accountsReceivable = DB::table('chart_of_accounts')->insertGetId(['id' => '112000', 'name' => 'Accounts Receivable', 'level' => 3, 'parent_id' => $currentAssets, 'type' => 'asset']);
       
            // Accounts Receivable subaccount
            DB::table('chart_of_accounts')->insert([
                ['id' => '112100', 'name' => 'Accounts Receivable - Customers', 'level' => 4, 'parent_id' => $accountsReceivable, 'type' => 'asset']
            ]);

       // Other subaccounts under Current Assets
       DB::table('chart_of_accounts')->insert([
           ['id' => '113000', 'name' => 'Inventory', 'level' => 3, 'parent_id' => $currentAssets, 'type' => 'asset'],
           ['id' => '114000', 'name' => 'Bank', 'level' => 3, 'parent_id' => $currentAssets, 'type' => 'asset']
       ]);
   
        
        $fixedAssets = DB::table('chart_of_accounts')->insertGetId(['id' => '120000', 'name' => 'Fixed Assets', 'level' => 2, 'parent_id' => $assets, 'type' => 'asset']);
        $otherAssets = DB::table('chart_of_accounts')->insertGetId(['id' => '130000', 'name' => 'Other Assets', 'level' => 2, 'parent_id' => $assets, 'type' => 'asset']);



        //200000
        // Main liabilities account
        $liabilities = DB::table('chart_of_accounts')->insertGetId(['id' => '200000', 'name' => 'Liabilities', 'level' => 1, 'type' => 'liability']);

        // Subcategory under Liabilities
        $currentLiabilities = DB::table('chart_of_accounts')->insertGetId(['id' => '210000', 'name' => 'Current Liabilities', 'level' => 2, 'parent_id' => $liabilities, 'type' => 'liability']);

            // Subcategories under Current Liabilities
            $accountsPayable = DB::table('chart_of_accounts')->insertGetId(['id' => '211000', 'name' => 'Accounts Payable', 'level' => 3, 'parent_id' => $currentLiabilities, 'type' => 'liability']);

            // Accounts Payable subaccount
            DB::table('chart_of_accounts')->insert([
                ['id' => '211100', 'name' => 'Accounts Payable - Suppliers', 'level' => 4, 'parent_id' => $accountsPayable, 'type' => 'liability']
            ]);

        // Other subaccounts under Current Liabilities
        DB::table('chart_of_accounts')->insert([
            ['id' => '212000', 'name' => 'Short-term Loans', 'level' => 3, 'parent_id' => $currentLiabilities, 'type' => 'liability']
        ]);

        // Long-term Liabilities
        $longTermLiabilities = DB::table('chart_of_accounts')->insertGetId(['id' => '220000', 'name' => 'Long-term Liabilities', 'level' => 2, 'parent_id' => $liabilities, 'type' => 'liability']);

        // Subaccounts under Long-term Liabilities
        DB::table('chart_of_accounts')->insert([
            ['id' => '221000', 'name' => 'Long-term Loans', 'level' => 3, 'parent_id' => $longTermLiabilities, 'type' => 'liability']
        ]);
    
        
        //300000
        // Main equity account
        $equity = DB::table('chart_of_accounts')->insertGetId(['id' => '300000', 'name' => 'Equity', 'level' => 1, 'type' => 'equity']);
        // Subcategories under Equity
        $ownersEquity = DB::table('chart_of_accounts')->insertGetId(['id' => '310000', 'name' => "Owner's Equity", 'level' => 2, 'parent_id' => $equity, 'type' => 'equity']);
        $retainedEarnings = DB::table('chart_of_accounts')->insertGetId(['id' => '320000', 'name' => 'Retained Earnings', 'level' => 2, 'parent_id' => $equity, 'type' => 'equity']);


        //400000
        // Main revenue account
        $revenue = DB::table('chart_of_accounts')->insertGetId(['id' => '400000', 'name' => 'Revenue', 'level' => 1, 'type' => 'revenue']);
        // Subcategories under Revenue
        $salesRevenue = DB::table('chart_of_accounts')->insertGetId(['id' => '410000', 'name' => 'Sales Revenue', 'level' => 2, 'parent_id' => $revenue, 'type' => 'revenue']);
        $serviceRevenue = DB::table('chart_of_accounts')->insertGetId(['id' => '420000', 'name' => 'Service Revenue', 'level' => 2, 'parent_id' => $revenue, 'type' => 'revenue']);
        $otherRevenue = DB::table('chart_of_accounts')->insertGetId(['id' => '430000', 'name' => 'Other Revenue', 'level' => 2, 'parent_id' => $revenue, 'type' => 'revenue']);



        //500000
        // Main expenses account
        $expenses = DB::table('chart_of_accounts')->insertGetId(['id' => '500000', 'name' => 'Expenses', 'level' => 1, 'type' => 'expense']);
        // Subcategories under Expenses
        $cogs = DB::table('chart_of_accounts')->insertGetId(['id' => '510000', 'name' => 'Cost of Goods Sold (COGS)', 'level' => 2, 'parent_id' => $expenses, 'type' => 'expense']);
        $operatingExpenses = DB::table('chart_of_accounts')->insertGetId(['id' => '520000', 'name' => 'Operating Expenses', 'level' => 2, 'parent_id' => $expenses, 'type' => 'expense']);
        $otherExpenses = DB::table('chart_of_accounts')->insertGetId(['id' => '530000', 'name' => 'Other Expenses', 'level' => 2, 'parent_id' => $expenses, 'type' => 'expense']);

    
    }
    
}
