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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_master_id')->nullable();
            $table->string('invoice_no', 50)->nullable();
            $table->date('date')->nullable();
            $table->string('type', 50)->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variation_id')->nullable();
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('unit_cost', 15, 2)->nullable();


            $table->string('variation_barcode', 100)->nullable();
            $table->string('purchase_category', 100)->nullable();
            $table->string('stock_barcode', 100)->nullable();
            

            $table->string('product_type', 50)->nullable();

            $table->integer('parent_no')->nullable();
            $table->integer('child_no')->nullable();

            $table->decimal('quantity', 15, 3)->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();

            $table->decimal('cost_amount', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('stock_quantity', 15, 2)->nullable();

            $table->decimal('discount_unit_price', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->string('discount_type',50)->nullable();
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->decimal('subtotal_after_discount', 15, 2)->nullable();
            $table->string('tax_type',50)->nullable();
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->decimal('tax_value', 15, 2)->nullable()->nullable();
            $table->decimal('subtotal_after_tax', 15, 2)->nullable();
            $table->decimal('grand_total', 15, 2)->nullable();

            $table->decimal('base_unit_value', 10, 2)->nullable();
            $table->decimal('base_unit_amount', 10, 2)->nullable();
            $table->decimal('base_unit_qty', 10, 3)->nullable();

            $table->decimal('child_unit_value', 10, 2)->nullable();
            $table->decimal('child_unit_amount', 10, 2)->nullable();
            $table->decimal('child_unit_qty', 10, 3)->nullable();

            $table->string('description')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
