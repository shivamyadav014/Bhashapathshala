<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('language'); // e.g., Spanish, French, German
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('thumbnail')->nullable();
            $table->integer('duration_hours')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
