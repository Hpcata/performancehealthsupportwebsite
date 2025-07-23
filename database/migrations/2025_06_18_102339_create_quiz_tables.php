<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table to track quiz attempts
        if (! Schema::hasTable('quiz')) {
            Schema::create('quiz', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('ip_address');
                $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
                $table->boolean('is_completed')->default(false);
                $table->integer('nutrition_score')->nullable();
                $table->string('nutrition_feedback')->nullable();
                $table->integer('sports_score')->nullable();
                $table->string('sports_feedback')->nullable();
                $table->integer('supplements_score')->nullable();
                $table->string('supplements_feedback')->nullable();
                $table->timestamp('started_at');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        // Table to store quiz answers
        if (! Schema::hasTable('quiz_answers')) {
            Schema::create('quiz_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_id')->constrained('quiz')->onDelete('cascade');
                $table->string('form_slug');
                $table->text('question');
                $table->integer('question_index');
                $table->integer('step');
                $table->json('options')->nullable();
                $table->json('answer')->nullable();
                $table->boolean('is_required')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('quiz_answers')) {
            Schema::dropIfExists('quiz_answers');
        }

        if (Schema::hasTable('quiz')) {
            Schema::dropIfExists('quiz');
        }
    }
};