<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateExistingAgeGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing age group values to new format
        DB::table('users')->where('age_group', '< 15')->update(['age_group' => '15-18']);
        DB::table('users')->where('age_group', '16 - 20')->update(['age_group' => '18-22']);
        DB::table('users')->where('age_group', '21 - 25')->update(['age_group' => '22-30']);
        DB::table('users')->where('age_group', '26 - 30')->update(['age_group' => 'over-30']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to old age group values
        DB::table('users')->where('age_group', '15-18')->update(['age_group' => '< 15']);
        DB::table('users')->where('age_group', '18-22')->update(['age_group' => '16 - 20']);
        DB::table('users')->where('age_group', '22-30')->update(['age_group' => '21 - 25']);
        DB::table('users')->where('age_group', 'over-30')->update(['age_group' => '26 - 30']);
    }
} 