<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Database\Seeders\Accounting\VoucherTypeSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();// ID of the user who created the record
            $table->unsignedBigInteger('updated_by')->nullable();// ID of the user who last updated the record
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('business_id')->nullable(); 
            $table->timestamps();
        });
        Artisan::call('db:seed', [
            '--class' => VoucherTypeSeeder::class
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_types');
    }
};
