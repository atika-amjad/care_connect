<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Therapist extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'years_experience',
        'rating',
        'availability',
        'tags',
        'icon',
        'bg_color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'years_experience' => 'integer',
            'rating' => 'decimal:1',
            'tags' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithTag($query, ?string $tag)
    {
        if (! $tag || $tag === 'all') {
            return $query;
        }

        return $query->whereJsonContains('tags', $tag);
    }
}
