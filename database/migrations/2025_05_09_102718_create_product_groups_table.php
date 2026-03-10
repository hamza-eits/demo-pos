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
        Schema::create('product_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->timestamps();
        });

        DB::table('product_groups')->insert([
            ['name' => 'Churidars'],
            ['name' => 'Latcha'],
            ['name' => 'Lehanga'],
            ['name' => 'Saree'],
            ['name' => 'Blouse'],
            ['name' => 'Kurthi'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_groups');
    }
};
