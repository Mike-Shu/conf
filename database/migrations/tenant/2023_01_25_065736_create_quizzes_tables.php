<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('quizzes', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->nullable()->unique();
            $table->string('slug')->unique();

            $table->string('title');
            $table->jsonb('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_questions', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('content')->nullable();
            $table->boolean('multiple')->default(false);
            $table->integer('sort')->unsigned()->nullable();
            $table->jsonb('data')->nullable();

            $table->timestamps();
        });

        Schema::create('quiz_question_answers', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('content')->nullable();
            $table->boolean('correct')->default(false);
            $table->integer('sort')->unsigned()->nullable();
            $table->jsonb('data')->nullable();

            $table->timestamps();
        });

        Schema::create('quiz_user_attempts', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('quiz_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('correct_answers')->nullable();
            $table->jsonb('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_user_attempts');
        Schema::dropIfExists('quiz_question_answers');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
