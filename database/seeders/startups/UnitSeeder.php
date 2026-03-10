<?php

namespace Database\Seeders\Startups;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        DB::table('units')->insert([
            [
                'base_unit' => 'KG',
                'base_unit_value' => '1',
                'child_unit' => 'g',
                'child_unit_value' => '1000',
                'operator' => '*',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'base_unit' => 'L',
                'base_unit_value' => '1',
                'child_unit' => 'ml',
                'child_unit_value' => '1000',
                'operator' => '*',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'base_unit' => 'Dozen',
                'base_unit_value' => '12',
                'child_unit' => 'pc',
                'child_unit_value' => '1',
                'operator' => '*',
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    
}
