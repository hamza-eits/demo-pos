<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Database\Seeders\Accounting\ChartOfAccountSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Account name (e.g., "Petty Cash")
            $table->string('description')->nullable(); // Optional description
            $table->integer('level');                  // Account level (e.g., 1, 2, 3, or 4)
            $table->unsignedBigInteger('parent_id')->nullable(); // Reference to parent account
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']); // Account type
            $table->string('category')->nullable();
            $table->boolean('is_lock')->default(1);
            $table->boolean('is_active')->default(1);
            
            $table->unsignedBigInteger('created_by')->nullable();// ID of the user who created the record
            $table->unsignedBigInteger('updated_by')->nullable();// ID of the user who last updated the record
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('business_id')->nullable(); 


            $table->foreign('parent_id')->references('id')->on('chart_of_accounts')->onDelete('cascade');
            $table->timestamps();
            
        });
        Artisan::call('db:seed', [
            '--class' => ChartOfAccountSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
