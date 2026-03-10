<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_master_id')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->string('mode')->nullable();
            $table->integer('number_of_payments')->nullable();
            $table->unsignedBigInteger('chart_of_account_id')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->timestamps();

            $table->foreign('invoice_master_id')->references('id')->on('invoice_masters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
