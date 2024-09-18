<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'bid_amount',
        'bid_status_id'
    ];

    protected $attributes = [
        'bid_status_id' => 1,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(BidStatus::class, 'id', 'bid_status_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
