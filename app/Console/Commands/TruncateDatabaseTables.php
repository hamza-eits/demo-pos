<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateDatabaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trancate Database Tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isAllowed = false;
        if(!$isAllowed){
            $this->info("Not Alowed");
            return ;
        }
        $data = null;
        // Disable foreign key checks (important if there are relationships)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('journals')->truncate();
        $data .= "Journals\n";


        DB::table('expense_details')->truncate();
        DB::table('expenses')->truncate();
        $data .= "expenses & expense_details\n"; 



        DB::table('voucher_details')->truncate();
        DB::table('vouchers')->truncate();
        $data .= "vouchers & voucher_details\n"; 


        DB::table('invoice_payments')->truncate(); 
        DB::table('invoice_details')->truncate();
        DB::table('invoice_masters')->truncate();


       


        

        $data .= "invoice_masters , invoice_details , invoice_payments \n"; 

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info("Given Tables are Truncated...!");
        $this->info($data);
       
    }


}
//  DB::table('brands')->truncate();
//         DB::table('categories')->truncate();
//         DB::table('categories')->truncate();
//         DB::table('products')->truncate();
//         DB::table('product_variations')->truncate();
//         DB::table('parties')->truncate();
//         DB::table('materials')->truncate();
//         DB::table('product_groups')->truncate();
//         DB::table('custom_fields')->truncate();
//         DB::table('product_models')->truncate();
//         DB::table('variations')->truncate();