<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $fillable = [
        'name',
        'language',
        'meditation_sessions_count',
    ];

    protected function casts(): array
    {
        return [
            'meditation_sessions_count' => 'integer',
        ];
    }

    public function moodEntries(): HasMany
    {
        return $this->hasMany(MoodEntry::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function supportGroups(): BelongsToMany
    {
        return $this->belongsToMany(SupportGroup::class, 'group_memberships')
            ->withTimestamps();
    }

    public function initials(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }
}
