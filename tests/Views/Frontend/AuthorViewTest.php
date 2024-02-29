<?php

namespace Tests\Views\Frontend;

use App\Authority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RecreateSearchIndex;
use Tests\TestCase;

class AuthorViewTest extends TestCase
{
    use RefreshDatabase;
    use RecreateSearchIndex;

    public function testGetIndex()
    {
        $response = $this->get('/autori');
        $response->assertStatus(200);
    }

    public function testGetSuggestions()
    {
        $response = $this->get('/autori/suggestions?search=test');
        $response->assertStatus(200);
    }

    public function testGetSuggestionsMissingParameter()
    {
        $response = $this->get('/autori/suggestions');
        $response->assertStatus(200);
    }

    public function testGetSuggestionsArrayParameter()
    {
        $response = $this->get('/autori/suggestions?search[]=test');
        $response->assertStatus(200);
    }

    public function testGetDetail()
    {
        $author = Authority::factory()->create();

        $response = $this->get(sprintf('/autor/%s', $author->id));
        $response->assertStatus(200);
    }
}
