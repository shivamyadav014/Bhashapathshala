<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->longText('submission_content');
            $table->decimal('score', 5, 2)->nullable();
            $table->longText('feedback')->nullable();
            $table->enum('status', ['submitted', 'reviewed', 'graded'])->default('submitted');
            $table->timestamp('submitted_at');
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_submissions');
    }
};
