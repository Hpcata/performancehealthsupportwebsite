<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add the following fields to `pre_plan_details`:
     * - start_date (text)
     * - end_date (text)
     * - step (int)
     * - step_fill (int)
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('pre_plan_details')) {
            Schema::table('pre_plan_details', function (Blueprint $table) {
                if (!Schema::hasColumn('pre_plan_details', 'start_date')) {
                    $table->text('start_date')->nullable()->after('answer');
                }
                if (!Schema::hasColumn('pre_plan_details', 'end_date')) {
                    $table->text('end_date')->nullable()->after('start_date');
                }
                if (!Schema::hasColumn('pre_plan_details', 'step')) {
                    $table->integer('step')->default(0)->after('end_date');
                }
                if (!Schema::hasColumn('pre_plan_details', 'step_fill')) {
                    $table->integer('step_fill')->default(0)->after('step');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * Drop the newly added columns if they exist.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('pre_plan_details')) {
            Schema::table('pre_plan_details', function (Blueprint $table) {
                if (Schema::hasColumn('pre_plan_details', 'start_date')) {
                    $table->dropColumn('start_date');
                }
                if (Schema::hasColumn('pre_plan_details', 'end_date')) {
                    $table->dropColumn('end_date');
                }
                if (Schema::hasColumn('pre_plan_details', 'step')) {
                    $table->dropColumn('step');
                }
                if (Schema::hasColumn('pre_plan_details', 'step_fill')) {
                    $table->dropColumn('step_fill');
                }
            });
        }
    }
};
