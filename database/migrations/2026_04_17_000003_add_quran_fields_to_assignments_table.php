<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('type')->nullable()->after('lesson_id');        // hifz | murajaah | tilawah
            $table->unsignedSmallInteger('surah_number')->nullable()->after('type');
            $table->unsignedSmallInteger('start_ayah')->nullable()->after('surah_number');
            $table->unsignedSmallInteger('end_ayah')->nullable()->after('start_ayah');
            $table->unsignedTinyInteger('juz_number')->nullable()->after('end_ayah');
            $table->string('status')->default('assigned')->after('due_date'); // assigned | completed | needs_repeat
            $table->tinyInteger('grade')->unsigned()->nullable()->after('status'); // 1-5

            $table->foreign('surah_number')->references('id')->on('surahs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['surah_number']);
            $table->dropColumn(['type', 'surah_number', 'start_ayah', 'end_ayah', 'juz_number', 'status', 'grade']);
        });
    }
};
