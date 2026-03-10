<?php

namespace Database\Seeders\Startups;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouses')->insert([
            [
                'name' => 'Main Warehouse',
                'phone' => '123-456-7890',
                'email' => 'mainwarehouse@example.com',
                'address' => '123 Main St, Cityville, Country',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Secondary Warehouse',
                'phone' => '098-765-4321',
                'email' => 'secondarywarehouse@example.com',
                'address' => '456 Secondary St, Townsville, Country',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Overseas Warehouse',
                'phone' => '555-123-4567',
                'email' => 'overseaswarehouse@example.com',
                'address' => '789 Overseas Rd, Metropolis, Country',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Backup Warehouse',
                'phone' => '555-987-6543',
                'email' => 'backupwarehouse@example.com',
                'address' => '101 Backup Lane, Hamlet, Country',
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
