<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'value'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopePopular($query, int $limit = 10, int $offset = 0)
    {
        // SELECT book_id, AVG(value) as avg_rating FROM ratings GROUP BY book_id ORDER BY avg_rating DESC LIMIT 10;
        return $this->select([
            'book_id',
            DB::raw("AVG(value) as avg_rating"),
            DB::raw("COUNT(book_id) as total_voters")
        ])
            ->groupBy('book_id')
            ->orderBy('avg_rating', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function scopeHigherVotes($query, int $limit = 10, int $offset = 0): array|Collection
    {
        return $this->select([
            'book_id',
            DB::raw("COUNT(book_id) as total_voters")
        ])
            ->groupBy('book_id')
            ->orderBy('total_voters', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public static function getPopularCount(): int
    {
        return self::distinct('book_id')->count();
    }
}
