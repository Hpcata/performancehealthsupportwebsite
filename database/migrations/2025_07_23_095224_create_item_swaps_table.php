<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('item_swaps')) {
            Schema::create('item_swaps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('swap_item_id');
                $table->unsignedBigInteger('item_id');
                $table->string('swap_item_qty', 50)->nullable();
                $table->string('swap_item_qty_unit', 50)->nullable();
                $table->timestamps();

                // Add foreign key constraints if needed:
                $table->foreign('swap_item_id')->references('id')->on('items')->onDelete('cascade');
                $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('item_swaps');
    }
};
