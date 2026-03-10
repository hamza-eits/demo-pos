<?php

namespace Database\Seeders\Startups;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'mobile_no' => '033309874587',
                'email' => 'demo@extbooks.com',
                'type' => 'admin',
                'password' => Hash::make('123456'),
                'hint' => '123456',  
            ]
            
           
        ]);
    }
}
