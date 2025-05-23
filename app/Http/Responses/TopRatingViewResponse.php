<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;

final class TopRatingViewResponse implements Arrayable
{

    public function __construct(
        private readonly int $authorId,
        private readonly string $name,
        private readonly int $totalVoters
    ) {
    }

    public function toArray(): array
    {
        return [
            'author_id' => $this->authorId,
            'author_name' => $this->name,
            'total_voters' => $this->totalVoters,
        ];
    }
}