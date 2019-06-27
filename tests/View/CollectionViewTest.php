<?php

namespace Tests\View;

use App\Collection;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CollectionViewTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndexSortedByName() {
        $user = factory(User::class)->create();

        $collections = [
            factory(Collection::class)->create([
                'name' => 'z',
                'publish' => true,
                'user_id' => $user->id,
            ]),
            factory(Collection::class)->create([
                'name' => 'a',
                'publish' => true,
                'user_id' => $user->id,
            ]),
        ];

        $response = $this->route('get', 'frontend.collection.index', ['sort_by' => 'name']);
        $data = $response->original->getData();

        $this->assertEquals($data['collections'][0]->id, $collections[1]->id);
        $this->assertEquals($data['collections'][1]->id, $collections[0]->id);
    }
}