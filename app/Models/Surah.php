<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id', 'name_ar', 'name_en', 'name_transliteration',
        'total_ayahs', 'juz_start', 'page_start', 'revelation_type',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'surah_number');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->id . '. ' . $this->name_en . ' (' . $this->name_ar . ') — ' . $this->total_ayahs . ' ayahs';
    }
}
