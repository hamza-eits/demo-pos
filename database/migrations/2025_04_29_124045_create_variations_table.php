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
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('values')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });


        DB::table('variations')->insert([
           
            [
                'name' => 'Size',
                'values' => json_encode(['S', 'M', 'L','XL','XXL']),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variations');
    }
};
