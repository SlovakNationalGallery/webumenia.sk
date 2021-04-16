<?php

namespace Tests\Views\Frontend;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleViewTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIndex()
    {
        $response = $this->get('/clanky');
        $response->assertStatus(200);
    }

    public function testGetSuggestions()
    {
        $response = $this->get('/clanky/suggestions');
        $response->assertStatus(200);
    }

    public function testGetDetail()
    {
        $article = factory(Article::class)->create();
        $response = $this->get(sprintf('/clanok/%s', $article->slug));
        $response->assertStatus(200);
    }
}
