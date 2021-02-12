<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\RecreateSearchIndex;

class SearchTest extends TestCase
{
    use RecreateSearchIndex;

    public function testPageNumberTooLargeDoesNotProduceError()
    {
        $response = $this->get('/katalog?page=99999');
        $response->assertStatus(200);
    }
}
