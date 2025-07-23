<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename quiz table to quizzes
        if (Schema::hasTable('quiz')) {
            Schema::rename('quiz', 'quizzes');
        }

        // Update foreign key in quiz_answers table
        if (Schema::hasTable('quiz_answers')) {
            Schema::table('quiz_answers', function (Blueprint $table) {
                $table->dropForeign(['quiz_id']); // Drop old FK
                $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade'); // Add new FK
            });
        }
    }

    public function down()
    {
        // Revert quizzes back to quiz
        if (Schema::hasTable('quizzes')) {
            Schema::rename('quizzes', 'quiz');
        }

        // Revert foreign key in quiz_answers
        if (Schema::hasTable('quiz_answers')) {
            Schema::table('quiz_answers', function (Blueprint $table) {
                $table->dropForeign(['quiz_id']);
                $table->foreign('quiz_id')->references('id')->on('quiz')->onDelete('cascade');
            });
        }
    }
};
