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
        Schema::create('purchase_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->timestamps();
        });

        DB::table('purchase_categories')->insert([
            ['name' => 'A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'C', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'D', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'E', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_categories');
    }
};
