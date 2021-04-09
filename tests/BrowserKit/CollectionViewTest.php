<?php

namespace Tests\BrowserKit;

use App\Collection;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;

class CollectionViewTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    public function testIndexSortedByName() {
        $user = factory(User::class)->create();

        $collectionB = factory(Collection::class)->create([
            'name' => 'b',
            'published_at' => '2000-01-01 00:00:00',
            'user_id' => $user->id,
        ]);
        $collectionA = factory(Collection::class)->create([
            'name' => 'a',
            'published_at' => '2000-01-01 00:00:00',
            'user_id' => $user->id,
        ]);

        $response = $this->route('get', 'frontend.collection.index', ['sort_by' => 'name']);
        $data = $response->original->getData();

        $this->assertCount(2, $data['collections']);
        $this->assertEquals($data['collections'][0]->id, $collectionA->id);
        $this->assertEquals($data['collections'][1]->id, $collectionB->id);
    }

    public function testIndexSortedByNameTranslationFallback() {
        $user = factory(User::class)->create();

        $collectionB = factory(Collection::class)->create([
            'name' => 'b',
            'published_at' => '2000-01-01 00:00:00',
            'user_id' => $user->id,
        ]);
        $collectionC = factory(Collection::class)->create([
            'name' => 'a',
            'name:en' => 'c',
            'published_at' => '2000-01-01 00:00:00',
            'user_id' => $user->id,
        ]);

        app()->setLocale('en'); // request en route
        $response = $this->route('get', 'frontend.collection.index', ['sort_by' => 'name']);
        $data = $response->original->getData();

        $this->assertCount(2, $data['collections']);
        $this->assertEquals($data['collections'][0]->id, $collectionB->id);
        $this->assertEquals($data['collections'][1]->id, $collectionC->id);
    }
}
