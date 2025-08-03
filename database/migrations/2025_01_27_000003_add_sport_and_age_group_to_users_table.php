<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSportAndAgeGroupToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->nullable()->after('free_user'); // athlete, coach, etc.
            $table->unsignedBigInteger('sport_game_id')->nullable()->after('user_type');
            $table->string('age_group')->nullable()->after('sport_game_id');
            
            // Add foreign key constraint
            $table->foreign('sport_game_id')->references('id')->on('sport_games')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sport_game_id']);
            $table->dropColumn(['user_type', 'sport_game_id', 'age_group']);
        });
    }
} 