<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pos_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tax_type')->nullable(); // percentage or fixed
            $table->decimal('tax_value', 8, 2)->default(0.00);
            $table->unsignedBigInteger('default_customer_id')->nullable();
            $table->string('currency')->nullable();
            $table->boolean('enable_receipt_printing')->default(true);
            $table->unsignedBigInteger('default_unit_id')->nullable();
            $table->timestamps();
        });

        DB::table('pos_settings')->insert([
            'tax_type' => 'inclusive',
            'tax_value' => 5,
            'default_customer_id' => null,
            'currency' => 'USD',
            'enable_receipt_printing' => true,
            'default_unit_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_settings');
    }
};
