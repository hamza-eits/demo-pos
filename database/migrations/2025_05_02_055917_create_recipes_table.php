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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();

            $table->unsignedBigInteger('product_variation_id')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('total_quantity', 10, 2)->nullable();
            $table->boolean('is_active')->default(1);
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();

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
        Schema::dropIfExists('recipes');
    }
};
