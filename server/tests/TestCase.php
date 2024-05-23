<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
