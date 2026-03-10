<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('expense_no')->unique();
            $table->string('reference_no')->nullable();
            $table->string('subject')->nullable();
            $table->string('expense_type')->nullable();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->unsignedBigInteger('party_id')->nullable()->comment('supplier');
            
            $table->unsignedBigInteger('chart_of_account_id')->nullable()->comment('account used to pay the expense');
            $table->longText('description')->nullable();

            $table->decimal('total_amount', 15, 2)->nullable(); 
            $table->decimal('total_tax_amount', 5, 2)->nullable();        
            $table->decimal('grand_total', 15, 2)->nullable();

            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('spend_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();// ID of the user who created the record
            $table->unsignedBigInteger('updated_by')->nullable();// ID of the user who last updated the record
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('business_id')->nullable(); 
            $table->unsignedBigInteger('tax_filing_id')->nullable();
            $table->integer('is_tax_filed')->default(0);
            $table->timestamps();


        });



        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id');
            $table->date('date')->nullable();
            $table->string('expense_no')->nullable();
            
            $table->unsignedBigInteger('chart_of_account_id')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('amount', 15, 2)->nullable(); 
            $table->decimal('tax_percentage', 5, 2)->nullable();        
            $table->string('tax_type')->nullable();    

            $table->decimal('tax_amount', 15, 2)->nullable(); 
            $table->decimal('total', 15, 2)->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();// ID of the user who created the record
            $table->unsignedBigInteger('updated_by')->nullable();// ID of the user who last updated the record
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('business_id')->nullable(); 
            
            $table->timestamps();

            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::table('expense_details')->delete();

        // Schema::table('expense_details', function (Blueprint $table) {
        //     $table->dropForeign(['expense_id']);
        // });

        Schema::dropIfExists('expense_details'); 
        Schema::dropIfExists('expenses');
    }
};
