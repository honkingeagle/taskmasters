<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'comments',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
