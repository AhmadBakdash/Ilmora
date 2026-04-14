<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->tinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
