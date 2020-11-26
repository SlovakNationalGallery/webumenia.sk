<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\RefreshSearchIndex;

class SearchTest extends TestCase
{
    use RefreshSearchIndex;


    public function testPageNumberTooLargeDoesNotProduceError()
    {
        $response = $this->get('/katalog?page=99999');
        $response->assertStatus(200);
    }
}
