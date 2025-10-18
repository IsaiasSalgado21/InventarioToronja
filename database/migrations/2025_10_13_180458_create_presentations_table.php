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
        Schema::create('presentations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('item_id');
        $table->string('sku', 100)->unique();
        $table->string('description', 200)->nullable();
        $table->integer('units_per_presentation')->default(1);
        $table->string('base_unit', 50)->nullable(); 
        $table->integer('stock_current')->default(0);
        $table->integer('stock_minimum')->default(0);
        $table->decimal('unit_price', 12, 2)->default(0.00);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presentations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
