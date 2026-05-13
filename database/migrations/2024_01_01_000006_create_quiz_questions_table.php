<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer', 'essay']);
            $table->json('options')->nullable();
            $table->longText('correct_answer');
            $table->text('explanation')->nullable();
            $table->integer('points')->default(1);
            $table->integer('order');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
