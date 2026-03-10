<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
 

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('model_has_roles')->truncate();   
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate(); 
        DB::table('permissions')->truncate();   

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
       
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
       

        $user = User::find(1);
        if ($user) {
            $user->assignRole('super-admin');
        }

        $user = User::find(2);
        if ($user) {
            $user->assignRole('admin');
        }

        $user = User::find(3);
        if ($user) {
            $user->assignRole('user');
        }



    }
}
