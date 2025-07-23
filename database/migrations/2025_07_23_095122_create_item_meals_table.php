<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('item_meals')) {
            Schema::create('item_meals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('meal_id');
                $table->integer('order')->nullable();
                $table->string('item_qty', 50)->nullable();
                $table->string('item_qty_unit', 50)->nullable();
                $table->decimal('carbs', 7, 2)->nullable();
                $table->decimal('protein', 7, 2)->nullable();
                $table->decimal('fat', 7, 2)->nullable();
                $table->string('energy', 10)->nullable();
                $table->json('selected_qty_unit')->nullable();
                $table->timestamps();

                // Add foreign key constraints if needed:
                $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
                $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('item_meals');
    }
};
