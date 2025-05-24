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
        'value' // TODO: change field  name to rating: preserved key
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopePopular($query, int $limit = 10, int $offset = 0)
    {
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

    public static function getPopularCount(): int
    {
        return self::distinct('book_id')->count();
    }
}
