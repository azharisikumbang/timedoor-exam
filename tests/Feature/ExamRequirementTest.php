<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExamRequirementTest extends TestCase
{
    use RefreshDatabase;
    public function testItUsePhpVersion81()
    {
        $this->assertStringStartsWith("8.1", PHP_VERSION);
    }

    public function testItUseLaravel10()
    {
        $this->assertStringStartsWith("10.", app()->version());
    }

    public function testItHasAtLeastThreePages()
    {
        $this->get('/')->assertOk();
        $this->get('/top-rating')->assertOk();
        $this->get('/rating/create')->assertOk();
    }

    public function testItUseMysqlAsDatabaseDriver()
    {

        if (app()->environment() != 'production')
        {
            $this->markTestSkipped("It use SQLITE on testing environment");
            return;
        }

        $this->assertSame('mysql', DB::getConfig("driver"));
    }
}
