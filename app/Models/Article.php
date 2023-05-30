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
        'source_id',
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

    public function source(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'source_id', 'id');
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

        $q->when(isset($filters['category_id']), function ($q) use ($filters) {
            $q->whereIn('category_id', $filters['category_id']);
        });

//        $q->when(isset($filters['summary']), function ($q) use ($filters) {
//            $q->limit(12);
//        });

        $q->when(isset($filters['user_id']), function ($q) use ($filters) {
            // try to personalize articles based on the user id provided ...

            $categories = UserCategoryPreference::where('user_id', $filters['user_id'])
                ->pluck('category_id')
                ->toArray();

            $authors = UserAuthorPreference::where('user_id', $filters['user_id'])
                ->pluck('author_id')
                ->toArray();

            $sources = UserSourcePreference::where('user_id', $filters['user_id'])
                ->pluck('source_id')
                ->toArray();

            if (!empty($categories)) {
                $q->whereIn('category_id', $categories);
            }

            if (!empty($authors)) {
                $q->orWhereIn('author_id', $authors);
            }

            if (!empty($sources)) {
                $q->orWhereIn('source_id', $sources);
            }

        });
    }
}
