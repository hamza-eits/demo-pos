<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            // $table->integer('business_id')->unsigned();
            // $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->string('type')->index(); // customer / supplier / both
            $table->string('party_type')->nullable();// individual or Business
            $table->string('business_name')->nullable();
            
            $table->string('contact_person')->nullable();
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            
            $table->string('mobile')->nullable();
            $table->string('alternate_number')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();

            $table->string('tax_number')->nullable();
            $table->decimal('opening_balance', 22, 4)->nullable()->default(0);
            $table->enum('pay_term_type', ['days', 'months'])->nullable();
            $table->integer('credit_limit')->nullable();
            
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('shipping_address')->nullable();
            $table->boolean('is_active')->default(1);
            
            $table->boolean('is_default')->default(0);//for auto selected
            $table->unsignedBigInteger('created_by')->nullable();// ID of the user who created the record
            $table->unsignedBigInteger('updated_by')->nullable();// ID of the user who last updated the record
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('business_id')->nullable(); 
          

            // $table->integer('pay_term_number')->nullable();
            // $table->string('landmark')->nullable();
            // $table->string('position')->nullable();
            // $table->string('contact_id')->nullable();// 
            // $table->string('contact_status')->nullable();
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
