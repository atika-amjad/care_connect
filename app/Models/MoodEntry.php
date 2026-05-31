<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoodEntry extends Model
{
    protected $fillable = [
        'user_id',
        'score',
        'emoji',
        'label',
        'energy',
        'anxiety',
        'tags',
        'note',
        'logged_date',
        'logged_time',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'energy' => 'integer',
            'anxiety' => 'integer',
            'tags' => 'array',
            'logged_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function scoreColor(int $score): string
    {
        return match (true) {
            $score <= 1 => '#E05C6A',
            $score <= 3 => '#F5924E',
            $score <= 5 => '#4A9EDF',
            $score <= 7 => '#4CAF84',
            default => '#7C6FF7',
        };
    }
}
