<?php

use App\Models\InvoiceMaster;
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
        Schema::create('variation_barcodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_master_id');
            $table->unsignedBigInteger('invoice_detail_id');
            $table->string('variation_barcode', 100)->nullable();
            $table->string('purchase_category', 100)->nullable();
            $table->string('stock_barcode', 100)->nullable();
            $table->string('barcode', 100)->unique();
            $table->decimal('purchase_price', 10,2);
            $table->tinyInteger('is_printed')->default(0);

            $table->timestamps();

            $table->foreign('invoice_master_id')->references('id')->on('invoice_masters')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variation_barcodes');
    }

    
};
