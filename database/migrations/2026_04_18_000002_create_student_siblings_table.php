<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_siblings', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('sibling_id');
            $table->primary(['student_id', 'sibling_id']);
            $table->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('sibling_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_siblings');
    }
};
