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
            $table->enum('type', ['entry', 'exit'])->default('entry');
            $table->integer('quantity');
            $table->timestamp('movement_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
