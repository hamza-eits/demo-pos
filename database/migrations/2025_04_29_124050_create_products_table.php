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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('product_type', 50)->nullable();
            $table->string('variation_type', 50)->nullable();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();

            $table->unsignedBigInteger('product_group_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('custom_field_id')->nullable();
            $table->unsignedBigInteger('product_model_id')->nullable();

            $table->string('description')->nullable();

            $table->string('image')->nullable();

            $table->boolean('check_stock_qty')->default(1);
            $table->boolean('is_price_editable')->default(0);
            $table->boolean('is_decimal_qty_allowed')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_active')->default(1);

            $table->unsignedBigInteger('created_by')->nullable(); // ID of the user who created the record
            $table->unsignedBigInteger('updated_by')->nullable(); // ID of the user who last updated the record
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
        Schema::dropIfExists('products');
    }
};
