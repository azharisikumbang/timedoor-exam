<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->generateBookTitle(),
            'book_category_id' => mt_rand(1, 3000),
            'author_id' => mt_rand(1, 1000),
        ];
    }

    private function generateBookTitle(): string
    {
        $sentence = fake()->sentence(5);

        return substr($sentence, 0, strlen($sentence) - 1);
    }
}
