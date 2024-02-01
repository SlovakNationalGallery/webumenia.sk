<?php

namespace Tests\Feature\Api;

use App\Article;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    public function test_show()
    {
        $article = Article::factory()->create();

        $url = route('api.articles.show', $article);
        $this->get($url)
            ->assertJsonPath('data', [
                'id' => $article->id,
                'title' => $article->title,
                'summary' => $article->summary,
                'content' => $article->content,
            ]);
    }
}