<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sync_time'
    ];

    public function scopeFilterBy($q, $filters)
    {
        $q->when(isset($filters['search']), function ($q) use ($filters) {
            $q->where('name', 'LIKE', '%' . $filters['search'] . '%');
        });
    }
}
