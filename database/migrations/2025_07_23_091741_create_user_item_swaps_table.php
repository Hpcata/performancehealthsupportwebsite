<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('user_item_swaps')) {
            Schema::create('user_item_swaps', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('meal_id')->nullable();
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('swap_item_id');
                $table->string('qty', 50)->nullable();
                $table->string('unit', 50)->nullable();
                $table->decimal('carbs', 7, 2)->nullable();
                $table->decimal('protein', 7, 2)->nullable();
                $table->decimal('fat', 7, 2)->nullable();
                $table->string('energy', 10)->nullable();
                $table->json('selected_qty_unit')->nullable();
                $table->timestamps();

                // Foreign keys (optional)
                if (Schema::hasTable('items')) {
                    $table->foreign('item_id')->references('id')->on('items');
                    $table->foreign('swap_item_id')->references('id')->on('items');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_item_swaps')) {
            Schema::dropIfExists('user_item_swaps');
        }
    }
};
