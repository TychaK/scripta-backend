<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSourcePreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'source_id', 'id');
    }
}
