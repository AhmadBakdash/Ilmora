<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surahs', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('name_transliteration');
            $table->unsignedSmallInteger('total_ayahs');
            $table->unsignedTinyInteger('juz_start');
            $table->unsignedSmallInteger('page_start');
            $table->string('revelation_type'); // meccan | medinan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};
