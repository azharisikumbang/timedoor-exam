<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function totalVotes()
    {
        return $this->hasManyThrough(Rating::class, Book::class, 'author_id', 'book_id');
    }

    public function scopeTopAuthors($query, int $total = 10): Collection
    {
        return $this
            ->withCount('totalVotes')
            ->whereRaw(
                sprintf("id NOT IN (%s)", Book::select('author_id')->distinct()
                    ->whereRaw(
                        sprintf("id IN (%s)", Rating::select('book_id')->distinct()->where('value', '<=', 5)->toRawSql())
                    )
                    ->toRawSql())
            )
            ->whereNot('total_votes_count', 0)
            ->orderBy('total_votes_count', 'desc')
            ->limit($total)
            ->get();
    }
}
