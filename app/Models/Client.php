<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'api_key',
        'base_url'
    ];

    public function scopeFilterBy($q, $filters)
    {
        $q->when(isset($filters['search']), function ($q) use ($filters) {
            $q->where('name', 'LIKE', '%' . $filters['search'] . '%');
        });
    }
}
