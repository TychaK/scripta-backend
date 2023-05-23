<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'description',
        'url',
        'image_url',
        'published_at',
        'headline'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function scopeFilterBy($q, $filters)
    {
        $q->when(isset($filters['search']), function ($q) use ($filters) {
            $q->where('title', 'LIKE', '%' . $filters['search'] . '%');
            $q->orWhere('description', 'LIKE', '%' . $filters['search'] . '%');
            $q->orWhere('contributors', 'LIKE', '%' . $filters['search'] . '%');
            $q->whereHas('author', function ($q) use ($filters) {
                $q->orWhere('authors.name', 'LIKE', '%' . $filters['search'] . '%');
            });
        });
    }
}
