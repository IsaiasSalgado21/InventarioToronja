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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('presentation_id');
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('type', 50);
            $table->integer('quantity');
            $table->unsignedBigInteger('supplier_id')->nullable(); 
            $table->decimal('unit_cost', 12, 2)->nullable(); 
            $table->timestamp('movement_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('inventory_movements');
    }
};
