<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupportGroup extends Model
{
    protected $fillable = [
        'slug',
        'name_en',
        'name_ur',
        'description_en',
        'description_ur',
        'member_count',
        'icon',
        'bg_color',
    ];

    protected function casts(): array
    {
        return [
            'member_count' => 'integer',
        ];
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->withTimestamps();
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'ur' ? $this->name_ur : $this->name_en;
    }

    public function localizedDescription(): string
    {
        return app()->getLocale() === 'ur' ? $this->description_ur : $this->description_en;
    }
}
