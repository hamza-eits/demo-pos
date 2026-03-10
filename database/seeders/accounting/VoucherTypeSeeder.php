<?php

namespace Database\Seeders\Accounting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('voucher_types')->insert([
            [ 'code' => 'BP','name' => 'Bank Payment' ],
            [ 'code' => 'BR','name' => 'Bank Receipt' ],
            [ 'code' => 'CP','name' => 'Cash Payment' ],
            [ 'code' => 'CR','name' => 'Cash Receipt' ],
            [ 'code' => 'JV','name' => 'Journal Voucher'],
            
        ]);
    }
}
