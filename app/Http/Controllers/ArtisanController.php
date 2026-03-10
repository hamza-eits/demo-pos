<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        dd('ArtisanController');
        
        
        
        // Specify the Artisan commands you want to run
        $commands = [
            'make:model Startup/Company',
            'make:controller Startup/CompanyController -r',
            'make:seeder Startup/CompanySeeder',
            'make:migration create_companies_table',
        ];

        $output = '';
        foreach ($commands as $command) {
            // Run each command
            Artisan::call($command);
            $output .= Artisan::output() . '<br>';
        }
        
        // Return the output of the commands
        return response($output);
    }
}
