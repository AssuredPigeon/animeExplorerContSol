<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'mal_id',
        'title',
        'image_url',
        'score',
        'type',
        'year',
    ];

    protected $casts = [
        'mal_id' => 'integer',
        'score'  => 'float',
        'year'   => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
