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
        Schema::create('serving_tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number', 50)->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->string('location', 50)->nullable();
            $table->string('status', 50)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();

            $table->timestamps();
        });


        DB::table('serving_tables')->insert([
            [
                'table_number' => 'T01',
                'seating_capacity' => 4,
                'location' => 'Near Window',
                'status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'table_number' => 'T02',
                'seating_capacity' => 2,
                'location' => 'Center Area',
                'status' => 'Occupied',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'table_number' => 'T03',
                'seating_capacity' => 6,
                'location' => 'Corner',
                'status' => 'Reserved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serving_tables');
    }
};
