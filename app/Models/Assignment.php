<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assignment extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'assignment_status_id',
        'title',
        'description',
        'budget',
        'deadline',
    ];

    protected $attributes = [
        'assignment_status_id' => 1,
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function shouldBeSearchable(): bool
    {
        return $this->assignment_status_id === 1;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'assignment_skills');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(AssignmentStatus::class, 'assignment_status_id');
    }
}
