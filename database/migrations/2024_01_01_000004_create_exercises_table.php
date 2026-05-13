<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('exercise_type', ['listening', 'speaking', 'reading', 'writing', 'matching', 'multiple_choice']);
            $table->longText('content');
            $table->longText('instructions')->nullable();
            $table->json('hints')->nullable();
            $table->integer('difficulty_level')->default(1);
            $table->integer('points')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
