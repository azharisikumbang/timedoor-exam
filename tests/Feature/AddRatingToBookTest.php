<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddRatingToBookTest extends TestCase
{
    use RefreshDatabase;

    public function testSeeAddRatingForm()
    {
        $this->get('rating/create')
            ->assertOk()
            ->assertSee('rating')
            ->assertSee('author')
            ->assertSee('book');
    }
}
