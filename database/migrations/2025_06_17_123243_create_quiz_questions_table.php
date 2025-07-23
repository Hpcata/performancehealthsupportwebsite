<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->string('form_slug');
                $table->integer('question_index');
                $table->text('question_text');
                $table->json('options');
                $table->json('correct_answer');
                $table->timestamps();

                // Add unique constraint to prevent duplicate questions
                $table->unique(['form_slug', 'question_index']);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('quiz_questions')) {
            Schema::dropIfExists('quiz_questions');
        }
    }
};