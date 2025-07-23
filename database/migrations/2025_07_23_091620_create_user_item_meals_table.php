<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('user_item_meals')) {
            Schema::create('user_item_meals', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->bigInteger('user_id')->nullable();
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('meal_id');
                $table->boolean('is_swiped')->default(0);
                $table->string('qty', 50)->nullable();
                $table->string('unit', 50)->nullable();
                $table->decimal('carbs', 7, 2)->nullable();
                $table->decimal('protein', 7, 2)->nullable();
                $table->decimal('fat', 7, 2)->nullable();
                $table->string('energy', 10)->nullable();
                $table->json('selected_qty_unit')->nullable();
                $table->timestamps();

                // Foreign keys (optional, add if tables exist)
                if (Schema::hasTable('items')) {
                    $table->foreign('item_id')->references('id')->on('items');
                }
                if (Schema::hasTable('meals')) {
                    $table->foreign('meal_id')->references('id')->on('meals');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_item_meals')) {
            Schema::dropIfExists('user_item_meals');
        }
    }
};
