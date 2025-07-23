<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (!Schema::hasColumn('items', 'note')) $table->text('note')->nullable()->after('description');
                if (!Schema::hasColumn('items', 'protein')) $table->decimal('protein', 7, 2)->default(0.00)->after('note');
                if (!Schema::hasColumn('items', 'carbs')) $table->decimal('carbs', 7, 2)->default(0.00)->after('protein');
                if (!Schema::hasColumn('items', 'fat')) $table->decimal('fat', 7, 2)->nullable()->after('carbs');
                if (!Schema::hasColumn('items', 'energy')) $table->string('energy', 10)->nullable()->after('fat');
                if (!Schema::hasColumn('items', 'saturated')) $table->string('saturated', 10)->nullable()->after('energy');
                if (!Schema::hasColumn('items', 'sugars')) $table->string('sugars', 10)->nullable()->after('saturated');
                if (!Schema::hasColumn('items', 'dietary_fibre')) $table->string('dietary_fibre', 10)->nullable()->after('sugars');
                if (!Schema::hasColumn('items', 'sodium')) $table->string('sodium', 10)->nullable()->after('dietary_fibre');
                if (!Schema::hasColumn('items', 'serving_per_pack')) $table->string('serving_per_pack', 10)->nullable()->after('sodium');
                if (!Schema::hasColumn('items', 'serving_size')) $table->string('serving_size', 10)->nullable()->after('serving_per_pack');
                if (!Schema::hasColumn('items', 'serving_size_unit')) $table->string('serving_size_unit', 50)->nullable()->after('serving_size');
                if (!Schema::hasColumn('items', 'category')) $table->string('category', 50)->nullable()->after('serving_size_unit');
                if (!Schema::hasColumn('items', 'qty')) $table->string('qty', 50)->nullable()->after('image');
                if (!Schema::hasColumn('items', 'unit')) $table->string('unit', 50)->nullable()->after('qty');
                if (!Schema::hasColumn('items', 'selected_qty_unit')) $table->json('selected_qty_unit')->nullable()->after('unit');
                if (!Schema::hasColumn('items', 'is_swiped')) $table->boolean('is_swiped')->default(false)->after('selected_qty_unit');
                if (!Schema::hasColumn('items', 'is_extra')) $table->boolean('is_extra')->default(false)->after('is_swiped');
                if (!Schema::hasColumn('items', 'is_locked')) $table->boolean('is_locked')->default(false)->after('is_extra');
                if (!Schema::hasColumn('items', 'category_id')) $table->unsignedBigInteger('category_id')->nullable()->after('is_locked');
                if (!Schema::hasColumn('items', 'woolworth_json')) $table->json('woolworth_json')->nullable()->after('category_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn([
                    'note',
                    'protein',
                    'carbs',
                    'fat',
                    'energy',
                    'saturated',
                    'sugars',
                    'dietary_fibre',
                    'sodium',
                    'serving_per_pack',
                    'serving_size',
                    'serving_size_unit',
                    'category',
                    'qty',
                    'unit',
                    'selected_qty_unit',
                    'is_swiped',
                    'is_extra',
                    'is_locked',
                    'category_id',
                    'woolworth_json',
                ]);
            });
        }
    }
};
