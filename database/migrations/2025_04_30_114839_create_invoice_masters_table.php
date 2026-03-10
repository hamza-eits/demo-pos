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
        Schema::create('invoice_masters', function (Blueprint $table) {
            $table->id();

            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('type', 50)->nullable();

            $table->string('invoice_no')->unique();
            $table->string('reference_no')->nullable();
            $table->string('subject')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_mode')->nullable();

            $table->unsignedBigInteger('party_id')->nullable();
            $table->unsignedBigInteger('biller_id')->nullable();
            $table->unsignedBigInteger('waiter_id')->nullable();
            $table->unsignedBigInteger('saleman_id')->nullable();

            $table->decimal('total_quantity', 15, 2)->nullable();
            $table->decimal('total_cost_amount', 15, 2)->nullable();
            $table->decimal('subtotal_items', 15, 2)->nullable();
            $table->decimal('subtotal_addons', 15, 2)->nullable();
            $table->decimal('subtotal_before_discount', 15, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->decimal('subtotal_after_discount', 15, 2)->nullable();
            $table->string('tax_type')->nullable();
            $table->decimal('tax_value', 15, 2)->nullable();
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->decimal('subtotal_after_tax', 15, 2)->nullable();
            $table->decimal('tip_amount', 15, 2)->nullable();
            $table->decimal('shipping_fee', 15, 2)->nullable();
            $table->decimal('grand_total', 15, 2)->nullable();

            $table->string('serving_type')->nullable();
            $table->string('table_no')->nullable();
            $table->text('description')->nullable();

            $table->string('rider_name')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('customer_phone')->nullable();

            $table->tinyInteger('is_locked')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_masters');
    }
};
