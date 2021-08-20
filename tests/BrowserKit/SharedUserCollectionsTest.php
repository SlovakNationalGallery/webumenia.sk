<?php

use App\Item;
use App\SharedUserCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\BrowserKitTestCase;

class SharedUserCollectionsTest extends BrowserKitTestCase
{
    use DatabaseTransactions;
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreation()
    {
        $item = factory(Item::class)->create();

        $this->visitRoute('frontend.shared-user-collections.create', ['ids' => [$item->id]])
            ->see($item->title)
            ->type('Some name', 'name')
            ->type('Some description', 'description')
            ->press('shared-user-collection-submit');


        $collection = SharedUserCollection::first();

        $this
            ->seeRouteIs('frontend.shared-user-collections.edit', [
                'collection' => $collection,
                'token' => $collection->update_token
            ]);

    }

    public function testEditing()
    {
        $items = factory(Item::class, 2)->create();
        $collection = factory(SharedUserCollection::class)->create(['items' => $items->map->only('id')]);

        $this
            ->visitRoute(
                'frontend.shared-user-collections.edit', 
                ['collection' => $collection, 'token' => $collection->update_token]
            )
            ->type('Updated name', 'name')
            ->press('shared-user-collection-submit');

        $collection->fresh();

        $this->assertEquals('Updated name', $collection->name);
    }

}
