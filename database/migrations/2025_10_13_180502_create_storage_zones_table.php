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
    Schema::create('storage_zones', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->text('description')->nullable();
        $table->decimal('dimension_x', 6, 2)->default(0.00); // meters
        $table->decimal('dimension_y', 6, 2)->default(0.00); // meters
        $table->decimal('capacity_m2', 8, 2)->default(0.00);
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_zones', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
    }
};
