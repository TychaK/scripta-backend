<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAuthorPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
}
